const { chromium } = require('playwright');
const fs = require('fs-extra');
const path = require('path');

class FullPafenScraper {
    constructor() {
        this.baseUrl = 'https://pafen-dz.com';
        this.browser = null;
        this.page = null;
        this.data = {
            brands: [],
            categories: [],
            products: []
        };
        this.scrapedUrls = new Set();
    }

    async init() {
        console.log('🚀 Initializing Full Pafen scraper for complete product extraction...');
        
        try {
            this.browser = await chromium.launch({
                headless: false,
                slowMo: 50
            });
            
            this.page = await this.browser.newPage();
            
            await this.page.setExtraHTTPHeaders({
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            });
            await this.page.setViewportSize({ width: 1366, height: 768 });
            
            console.log('✅ Browser initialized successfully');
            
        } catch (error) {
            console.error('❌ Browser initialization failed:', error.message);
            throw error;
        }
    }

    async scrapeAll() {
        console.log('🔍 Starting complete scraping with prices...');
        
        try {
            // Navigate to homepage
            console.log(`🌐 Navigating to ${this.baseUrl}...`);
            await this.page.goto(this.baseUrl, { 
                waitUntil: 'domcontentloaded',
                timeout: 60000 
            });
            await this.page.waitForTimeout(3000);
            console.log('✅ Homepage loaded');

            // Extract categories
            await this.extractCategories();
            
            // Extract all products with detailed information including prices
            await this.scrapeAllProducts();
            
            // Extract brands from products
            await this.extractBrandsFromProducts();
            
        } catch (error) {
            console.error('❌ Full scraping failed:', error.message);
            throw error;
        }
    }

    async extractCategories() {
        console.log('📂 Extracting categories...');
        
        const categories = new Map();
        
        try {
            const elements = await this.page.$$('a[href*="/category/"]');
            console.log(`Found ${elements.length} category links`);
            
            for (const element of elements) {
                const href = await element.getAttribute('href');
                const text = await element.textContent();
                
                if (href && text && text.trim().length > 0) {
                    const cleanText = text.trim();
                    if (!this.isNonCategoryLink(cleanText, href)) {
                        const categoryId = href.match(/\/category\/(\d+)\//)?.[1];
                        const categoryKey = `${categoryId}_${this.createSlug(cleanText)}`;
                        
                        categories.set(categoryKey, {
                            id: categoryId,
                            name: cleanText,
                            slug: this.createSlug(cleanText),
                            url: href.startsWith('http') ? href : `${this.baseUrl}${href}`,
                            description: null,
                            image_url: null,
                            parent_id: null,
                            is_active: true,
                            sort_order: categories.size + 1
                        });
                    }
                }
            }
        } catch (error) {
            console.log(`⚠️ Error extracting categories:`, error.message);
        }

        this.data.categories = Array.from(categories.values());
        console.log(`✅ Found ${this.data.categories.length} unique categories`);
    }

    async scrapeAllProducts() {
        console.log('🛍️ Scraping ALL products with prices...');
        
        // Visit all category pages to get product URLs
        for (const category of this.data.categories) {
            try {
                console.log(`📖 Visiting category: ${category.name}...`);
                await this.page.goto(category.url, { 
                    waitUntil: 'domcontentloaded',
                    timeout: 30000 
                });
                await this.page.waitForTimeout(2000);
                
                // Get all product URLs from this category
                await this.collectProductUrls(category.name);
                
            } catch (error) {
                console.log(`⚠️ Error scraping category ${category.name}:`, error.message);
            }
        }

        console.log(`🔗 Found ${this.scrapedUrls.size} unique product URLs`);
        
        // Now visit each product page to get detailed information
        let productCount = 0;
        const maxProducts = 50; // Limit for testing, remove or increase for full scrape
        
        for (const productUrl of this.scrapedUrls) {
            if (productCount >= maxProducts) {
                console.log(`⏹️ Reached limit of ${maxProducts} products for testing`);
                break;
            }
            
            try {
                const productData = await this.scrapeProductDetails(productUrl);
                if (productData) {
                    this.data.products.push(productData);
                    productCount++;
                    console.log(`✅ [${productCount}/${Math.min(this.scrapedUrls.size, maxProducts)}] ${productData.name} - ${productData.price} DZD`);
                }
                
                // Small delay between product pages
                await this.page.waitForTimeout(1000);
                
            } catch (error) {
                console.log(`⚠️ Error scraping product ${productUrl}:`, error.message);
            }
        }
    }

    async collectProductUrls(categoryName) {
        try {
            const productLinks = await this.page.$$('a[href*="/product/"]');
            console.log(`Found ${productLinks.length} product links in ${categoryName}`);
            
            for (const link of productLinks) {
                const href = await link.getAttribute('href');
                if (href) {
                    const fullUrl = href.startsWith('http') ? href : `${this.baseUrl}${href}`;
                    this.scrapedUrls.add(fullUrl);
                }
            }
        } catch (error) {
            console.log(`⚠️ Error collecting product URLs from ${categoryName}:`, error.message);
        }
    }

    async scrapeProductDetails(productUrl) {
        try {
            console.log(`🔍 Scraping: ${productUrl}`);
            
            await this.page.goto(productUrl, { 
                waitUntil: 'domcontentloaded',
                timeout: 30000 
            });
            await this.page.waitForTimeout(1500);
            
            // Extract product name
            const nameSelectors = [
                'h1', 'h2',
                '.product-title',
                '.product-name',
                '[itemprop="name"]'
            ];
            
            let name = '';
            for (const selector of nameSelectors) {
                try {
                    const element = await this.page.$(selector);
                    if (element) {
                        const text = await element.textContent();
                        if (text && text.trim().length > 0) {
                            name = text.trim();
                            break;
                        }
                    }
                } catch (e) {}
            }
            
            // Extract price - looking for DZD format
            const priceSelectors = [
                '.price',
                '.product-price',
                '.amount',
                '[itemprop="price"]',
                'span:has-text("DZD")',
                'div:has-text("DZD")',
                'p:has-text("DZD")'
            ];
            
            let price = 0;
            let priceText = '';
            
            for (const selector of priceSelectors) {
                try {
                    const elements = await this.page.$$(selector);
                    for (const element of elements) {
                        const text = await element.textContent();
                        if (text && text.includes('DZD')) {
                            priceText = text.trim();
                            // Extract numeric value from "19 500,00 DZD" format
                            const match = priceText.match(/([\d\s,]+)(?=\s*DZD)/);
                            if (match) {
                                const numericString = match[1].replace(/\s/g, '').replace(',', '.');
                                price = parseFloat(numericString) || 0;
                                break;
                            }
                        }
                    }
                    if (price > 0) break;
                } catch (e) {}
            }
            
            // Extract description
            const descSelectors = [
                '.product-description',
                '.description',
                '[itemprop="description"]',
                '.product-details'
            ];
            
            let description = '';
            for (const selector of descSelectors) {
                try {
                    const element = await this.page.$(selector);
                    if (element) {
                        const text = await element.textContent();
                        if (text && text.trim().length > 0) {
                            description = text.trim().slice(0, 500); // Limit description length
                            break;
                        }
                    }
                } catch (e) {}
            }
            
            // Extract image
            const imgSelectors = [
                '.product-image img',
                '.product-img img', 
                'img[itemprop="image"]',
                '.main-image img'
            ];
            
            let imageUrl = '';
            for (const selector of imgSelectors) {
                try {
                    const element = await this.page.$(selector);
                    if (element) {
                        const src = await element.getAttribute('src');
                        if (src) {
                            imageUrl = src.startsWith('http') ? src : `${this.baseUrl}${src}`;
                            break;
                        }
                    }
                } catch (e) {}
            }

            // Extract brand from product name
            const brand = this.extractBrandFromName(name);
            
            if (name) {
                return {
                    name: name,
                    slug: this.createSlug(name),
                    description: description || null,
                    price: price,
                    price_text: priceText,
                    currency: 'DZD',
                    image_url: imageUrl || null,
                    brand: brand,
                    sku: this.extractSkuFromUrl(productUrl),
                    is_active: true,
                    stock_quantity: 0,
                    url: productUrl
                };
            }
            
        } catch (error) {
            console.log(`⚠️ Error scraping product details from ${productUrl}:`, error.message);
        }
        
        return null;
    }

    extractBrandFromName(name) {
        const commonBrands = [
            'DIOR', 'CHANEL', 'YSL', 'YVES SAINT LAURENT', 'GUERLAIN',
            'LANCOME', 'VERSACE', 'ARMANI', 'CALVIN KLEIN', 'HUGO BOSS',
            'PACO RABANNE', 'JEAN PAUL GAULTIER', 'THIERRY MUGLER',
            'NISHANE', 'CREED', 'TOM FORD', 'BURBERRY', 'DOLCE GABBANA',
            'GIVENCHY', 'KENZO', 'ISSEY MIYAKE', 'RALPH LAUREN'
        ];
        
        const nameUpper = name.toUpperCase();
        for (const brand of commonBrands) {
            if (nameUpper.includes(brand)) {
                return brand;
            }
        }
        
        // Try to extract first word as brand
        const firstWord = name.split(' ')[0];
        if (firstWord.length > 2) {
            return firstWord.toUpperCase();
        }
        
        return null;
    }

    extractSkuFromUrl(url) {
        const match = url.match(/\/product\/(\d+)-/);
        return match ? match[1] : null;
    }

    async extractBrandsFromProducts() {
        console.log('🏷️ Extracting brands from products...');
        
        const brandMap = new Map();
        
        for (const product of this.data.products) {
            if (product.brand) {
                brandMap.set(product.brand, {
                    name: product.brand,
                    slug: this.createSlug(product.brand),
                    description: null,
                    logo_url: null,
                    website_url: null,
                    is_active: true,
                    sort_order: brandMap.size + 1
                });
            }
        }
        
        this.data.brands = Array.from(brandMap.values());
        console.log(`✅ Found ${this.data.brands.length} brands from products`);
    }

    isNonCategoryLink(text, href) {
        const excludeTexts = [
            'mon compte', 'account', 'login', 'register',
            'français', 'english', 'عربي', 'contact', 'about',
            'aide', 'help', 'panier', 'cart', 'checkout',
            'search', 'recherche', 'accueil', 'boutique',
            's\'identifier', 's\'inscrire', 'achetez maintenant'
        ];
        
        const textLower = text.toLowerCase();
        return excludeTexts.some(exclude => textLower.includes(exclude));
    }

    createSlug(text) {
        return text
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    async saveData() {
        console.log('💾 Saving complete scraped data...');
        
        const outputDir = path.join(__dirname, 'scraped-data');
        await fs.ensureDir(outputDir);
        
        // Save individual files
        await fs.writeJSON(path.join(outputDir, 'brands-full.json'), this.data.brands, { spaces: 2 });
        await fs.writeJSON(path.join(outputDir, 'categories-full.json'), this.data.categories, { spaces: 2 });
        await fs.writeJSON(path.join(outputDir, 'products-full.json'), this.data.products, { spaces: 2 });
        
        // Save combined data
        await fs.writeJSON(path.join(outputDir, 'pafen-data-full.json'), this.data, { spaces: 2 });
        
        // Save summary
        const summary = {
            scraping_date: new Date().toISOString(),
            source_website: this.baseUrl,
            scraper_used: 'Full Playwright with Price Extraction',
            total_brands: this.data.brands.length,
            total_categories: this.data.categories.length,
            total_products: this.data.products.length,
            brands_with_logos: this.data.brands.filter(b => b.logo_url).length,
            products_with_images: this.data.products.filter(p => p.image_url).length,
            products_with_prices: this.data.products.filter(p => p.price > 0).length,
            average_price: this.data.products.length > 0 ? 
                this.data.products.reduce((sum, p) => sum + p.price, 0) / this.data.products.length : 0,
            price_range: {
                min: Math.min(...this.data.products.map(p => p.price)),
                max: Math.max(...this.data.products.map(p => p.price))
            }
        };
        
        await fs.writeJSON(path.join(outputDir, 'scraping-summary-full.json'), summary, { spaces: 2 });
        
        console.log('✅ Data saved successfully');
        console.log(`📊 Summary: ${summary.total_brands} brands, ${summary.total_categories} categories, ${summary.total_products} products`);
        console.log(`💰 Price info: ${summary.products_with_prices} products with prices, avg: ${summary.average_price.toFixed(2)} DZD`);
        console.log(`📈 Price range: ${summary.price_range.min} - ${summary.price_range.max} DZD`);
    }

    async close() {
        if (this.browser) {
            await this.browser.close();
            console.log('🔒 Browser closed');
        }
    }

    async run() {
        try {
            await this.init();
            await this.scrapeAll();
            await this.saveData();
        } catch (error) {
            console.error('❌ Full scraping failed:', error.message);
            throw error;
        } finally {
            await this.close();
        }
    }
}

// CLI execution
async function main() {
    const scraper = new FullPafenScraper();
    
    try {
        await scraper.run();
        console.log('🎉 Full scraping with prices completed successfully!');
    } catch (error) {
        console.error('💥 Full scraping failed:', error.message);
        process.exit(1);
    }
}

// Run if this file is executed directly
if (require.main === module) {
    main();
}

module.exports = FullPafenScraper;