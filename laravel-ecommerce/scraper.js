const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

class PafenScraper {
    constructor() {
        this.baseUrl = 'https://pafen-dz.com';
        this.browser = null;
        this.page = null;
        this.data = {
            brands: [],
            categories: [],
            products: []
        };
    }

    async init() {
        console.log('🚀 Initializing Pafen scraper...');
        this.browser = await puppeteer.launch({
            headless: false, // Set to true for production
            slowMo: 100,
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-accelerated-2d-canvas',
                '--no-first-run',
                '--no-zygote',
                '--disable-gpu'
            ]
        });
        this.page = await this.browser.newPage();
        
        // Set user agent to avoid blocking
        await this.page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        
        // Set viewport
        await this.page.setViewport({ width: 1366, height: 768 });
        
        console.log('✅ Browser initialized successfully');
    }

    async scrapebrands() {
        console.log('📋 Starting brands scraping...');
        
        try {
            // Navigate to the main page first
            await this.page.goto(this.baseUrl, { 
                waitUntil: 'networkidle2',
                timeout: 30000 
            });
            
            console.log('🔍 Looking for brands section...');
            
            // Try to find brands/marques link or section
            const brandsSelectors = [
                'a[href*="marque"]',
                'a[href*="brand"]',
                '.brands a',
                '.marques a',
                'nav a[href*="marque"]',
                '.menu a[href*="marque"]'
            ];
            
            let brandsFound = false;
            let brandLinks = [];
            
            for (const selector of brandsSelectors) {
                try {
                    await this.page.waitForSelector(selector, { timeout: 5000 });
                    brandLinks = await this.page.$$eval(selector, links => 
                        links.map(link => ({
                            name: link.textContent.trim(),
                            url: link.href,
                            logo: null
                        }))
                    );
                    
                    if (brandLinks.length > 0) {
                        console.log(`✅ Found ${brandLinks.length} brand links with selector: ${selector}`);
                        brandsFound = true;
                        break;
                    }
                } catch (e) {
                    console.log(`❌ Selector ${selector} not found, trying next...`);
                }
            }
            
            // If no brand links found, try to find brands page
            if (!brandsFound) {
                console.log('🔍 Looking for brands page in navigation...');
                
                // Try to find and click on brands/marques page
                const navSelectors = [
                    'a[href*="/marques"]',
                    'a[href*="/brands"]',
                    'a:contains("Marques")',
                    'a:contains("Brands")',
                    '.nav a[href*="marque"]'
                ];
                
                for (const selector of navSelectors) {
                    try {
                        const brandsPageLink = await this.page.$(selector);
                        if (brandsPageLink) {
                            console.log(`📍 Found brands page link, navigating...`);
                            await brandsPageLink.click();
                            await this.page.waitForNavigation({ waitUntil: 'networkidle2' });
                            
                            // Now try to scrape brands from the brands page
                            brandLinks = await this.scrapeBrandsFromPage();
                            brandsFound = true;
                            break;
                        }
                    } catch (e) {
                        console.log(`❌ Could not navigate with selector: ${selector}`);
                    }
                }
            }
            
            // If still no brands found, try to scrape from product pages or footer
            if (!brandsFound) {
                console.log('🔍 Trying alternative brand discovery methods...');
                brandLinks = await this.discoverBrandsFromProducts();
            }
            
            // Process each brand to get logos
            if (brandLinks.length > 0) {
                console.log(`🔄 Processing ${brandLinks.length} brands to get logos...`);
                
                for (let i = 0; i < brandLinks.length; i++) {
                    const brand = brandLinks[i];
                    console.log(`📦 Processing brand ${i + 1}/${brandLinks.length}: ${brand.name}`);
                    
                    try {
                        // Try to get brand logo
                        if (brand.url && brand.url !== this.baseUrl) {
                            await this.page.goto(brand.url, { 
                                waitUntil: 'networkidle2',
                                timeout: 15000 
                            });
                            
                            // Look for brand logo
                            const logoSelectors = [
                                '.brand-logo img',
                                '.marque-logo img',
                                '.brand-header img',
                                '.logo img',
                                'img[alt*="logo"]',
                                'img[src*="logo"]',
                                'img[src*="brand"]',
                                'img[src*="marque"]'
                            ];
                            
                            for (const logoSelector of logoSelectors) {
                                try {
                                    const logo = await this.page.$eval(logoSelector, img => img.src);
                                    if (logo) {
                                        brand.logo = logo;
                                        console.log(`✅ Found logo for ${brand.name}`);
                                        break;
                                    }
                                } catch (e) {
                                    // Continue to next selector
                                }
                            }
                        }
                        
                        this.data.brands.push({
                            name: brand.name,
                            slug: this.createSlug(brand.name),
                            logo_url: brand.logo,
                            url: brand.url,
                            is_active: true,
                            sort_order: i + 1
                        });
                        
                    } catch (error) {
                        console.log(`❌ Error processing brand ${brand.name}: ${error.message}`);
                        
                        // Add brand without logo
                        this.data.brands.push({
                            name: brand.name,
                            slug: this.createSlug(brand.name),
                            logo_url: null,
                            url: brand.url,
                            is_active: true,
                            sort_order: i + 1
                        });
                    }
                    
                    // Small delay to avoid overwhelming the server
                    await this.delay(1000);
                }
            }
            
            console.log(`✅ Brands scraping completed! Found ${this.data.brands.length} brands`);
            
        } catch (error) {
            console.error('❌ Error during brands scraping:', error);
        }
    }

    async scrapeBrandsFromPage() {
        console.log('🔍 Scraping brands from current page...');
        
        const brandSelectors = [
            '.brand-item',
            '.marque-item', 
            '.brand-card',
            '.brand-link',
            '.brands-grid .item',
            '.marques-grid .item',
            '.brand-list li',
            '.brands-list li'
        ];
        
        for (const selector of brandSelectors) {
            try {
                const brands = await this.page.$$eval(selector, items => {
                    return items.map(item => {
                        const nameEl = item.querySelector('h3, h4, .name, .title, a') || item;
                        const linkEl = item.querySelector('a') || item;
                        const logoEl = item.querySelector('img');
                        
                        return {
                            name: nameEl.textContent.trim(),
                            url: linkEl.href || null,
                            logo: logoEl ? logoEl.src : null
                        };
                    });
                });
                
                if (brands.length > 0) {
                    console.log(`✅ Found ${brands.length} brands using selector: ${selector}`);
                    return brands;
                }
            } catch (e) {
                console.log(`❌ Selector ${selector} failed, trying next...`);
            }
        }
        
        return [];
    }

    async discoverBrandsFromProducts() {
        console.log('🔍 Discovering brands from products...');
        
        // Try to find product pages first
        const productSelectors = [
            'a[href*="/product"]',
            'a[href*="/produit"]', 
            '.product-item a',
            '.produit-item a',
            '.product-card a'
        ];
        
        const brands = new Set();
        
        for (const selector of productSelectors) {
            try {
                const productLinks = await this.page.$$eval(selector, links => 
                    links.slice(0, 10).map(link => link.href) // Limit to first 10 products
                );
                
                if (productLinks.length > 0) {
                    console.log(`📦 Found ${productLinks.length} product links, checking for brands...`);
                    
                    for (const productUrl of productLinks.slice(0, 5)) { // Check first 5 products
                        try {
                            await this.page.goto(productUrl, { 
                                waitUntil: 'networkidle2',
                                timeout: 10000 
                            });
                            
                            // Look for brand information
                            const brandSelectors = [
                                '.product-brand',
                                '.brand-name',
                                '.marque',
                                '[data-brand]',
                                '.brand a',
                                '.product-info .brand'
                            ];
                            
                            for (const brandSelector of brandSelectors) {
                                try {
                                    const brandName = await this.page.$eval(brandSelector, el => el.textContent.trim());
                                    if (brandName && brandName.length > 1) {
                                        brands.add(brandName);
                                        console.log(`✅ Found brand: ${brandName}`);
                                    }
                                } catch (e) {
                                    // Continue
                                }
                            }
                            
                        } catch (error) {
                            console.log(`❌ Error checking product: ${productUrl}`);
                        }
                    }
                    
                    break; // If we found product links, no need to try other selectors
                }
            } catch (e) {
                console.log(`❌ Product selector ${selector} failed`);
            }
        }
        
        return Array.from(brands).map(brandName => ({
            name: brandName,
            url: null,
            logo: null
        }));
    }

    async scrapeCategories() {
        console.log('📂 Starting categories scraping...');
        // TODO: Implement categories scraping
    }

    async scrapeProducts() {
        console.log('🛍️ Starting products scraping...');
        // TODO: Implement products scraping
    }

    createSlug(text) {
        return text
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '') // Remove accents
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
    }

    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async saveData() {
        console.log('💾 Saving scraped data...');
        
        const outputDir = './scraped-data';
        if (!fs.existsSync(outputDir)) {
            fs.mkdirSync(outputDir);
        }
        
        // Save brands
        if (this.data.brands.length > 0) {
            const brandsFile = path.join(outputDir, 'brands.json');
            fs.writeFileSync(brandsFile, JSON.stringify(this.data.brands, null, 2));
            console.log(`✅ Saved ${this.data.brands.length} brands to ${brandsFile}`);
        }
        
        // Save categories
        if (this.data.categories.length > 0) {
            const categoriesFile = path.join(outputDir, 'categories.json');
            fs.writeFileSync(categoriesFile, JSON.stringify(this.data.categories, null, 2));
            console.log(`✅ Saved ${this.data.categories.length} categories to ${categoriesFile}`);
        }
        
        // Save products
        if (this.data.products.length > 0) {
            const productsFile = path.join(outputDir, 'products.json');
            fs.writeFileSync(productsFile, JSON.stringify(this.data.products, null, 2));
            console.log(`✅ Saved ${this.data.products.length} products to ${productsFile}`);
        }
        
        // Save combined data
        const allDataFile = path.join(outputDir, 'all-data.json');
        fs.writeFileSync(allDataFile, JSON.stringify(this.data, null, 2));
        console.log(`✅ Saved all data to ${allDataFile}`);
    }

    async cleanup() {
        if (this.browser) {
            await this.browser.close();
            console.log('🧹 Browser closed');
        }
    }

    async run() {
        try {
            await this.init();
            
            // Scrape brands first
            await this.scrapeCategories();
            
            // Then scrape categories
            await this.scrapeCategories();
            
            // Finally scrape products
            await this.scrapeProducts();
            
            // Save all data
            await this.saveData();
            
        } catch (error) {
            console.error('❌ Scraping failed:', error);
        } finally {
            await this.cleanup();
        }
    }
}

// Run the scraper
async function main() {
    console.log('🎯 Starting Pafen scraper...');
    
    const scraper = new PafenScraper();
    await scraper.run();
    
    console.log('🎉 Scraping completed!');
}

// Handle process termination
process.on('SIGINT', async () => {
    console.log('\n🛑 Received SIGINT, cleaning up...');
    process.exit(0);
});

process.on('SIGTERM', async () => {
    console.log('\n🛑 Received SIGTERM, cleaning up...');
    process.exit(0);
});

// Run only if this file is executed directly
if (require.main === module) {
    main().catch(console.error);
}

module.exports = PafenScraper;