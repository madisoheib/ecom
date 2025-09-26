const { chromium } = require('playwright');
const fs = require('fs-extra');
const path = require('path');

class AdvancedPafenScraper {
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
        console.log('🚀 Initializing Advanced Playwright Pafen scraper...');
        
        try {
            this.browser = await chromium.launch({
                headless: false,
                slowMo: 100
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

    async scrapeCategoriesAndProducts() {
        console.log('🔍 Starting comprehensive scraping...');
        
        try {
            // Navigate to homepage
            console.log(`🌐 Navigating to ${this.baseUrl}...`);
            await this.page.goto(this.baseUrl, { 
                waitUntil: 'domcontentloaded',
                timeout: 60000 
            });
            await this.page.waitForTimeout(3000);
            console.log('✅ Homepage loaded');

            // Extract categories from homepage
            await this.extractCategories();
            
            // Visit a few main category pages to get products
            await this.scrapeProductsFromCategories();
            
            // Look for brands
            await this.extractBrands();
            
        } catch (error) {
            console.error('❌ Scraping failed:', error.message);
            throw error;
        }
    }

    async extractCategories() {
        console.log('📂 Extracting categories...');
        
        const categorySelectors = [
            'a[href*="/category/"]'
        ];

        const categories = new Map();
        
        for (const selector of categorySelectors) {
            try {
                const elements = await this.page.$$(selector);
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
                console.log(`⚠️ Error with category selector ${selector}:`, error.message);
            }
        }

        this.data.categories = Array.from(categories.values());
        console.log(`✅ Found ${this.data.categories.length} unique categories`);
    }

    async scrapeProductsFromCategories() {
        console.log('🛍️ Scraping products from category pages...');
        
        // Get first 3 categories to scrape products from
        const categoriesToScrape = this.data.categories.slice(0, 3);
        
        for (const category of categoriesToScrape) {
            try {
                console.log(`📖 Visiting category: ${category.name}...`);
                await this.page.goto(category.url, { 
                    waitUntil: 'domcontentloaded',
                    timeout: 30000 
                });
                await this.page.waitForTimeout(2000);
                
                // Extract products from this category page
                await this.extractProductsFromCurrentPage(category.name);
                
            } catch (error) {
                console.log(`⚠️ Error scraping category ${category.name}:`, error.message);
            }
        }
    }

    async extractProductsFromCurrentPage(categoryName) {
        const productSelectors = [
            '.product-item',
            '.product-card',
            '.product',
            '.item',
            '[data-product]',
            '.product-container',
            '.card'
        ];

        for (const selector of productSelectors) {
            try {
                const elements = await this.page.$$(selector);
                console.log(`Found ${elements.length} elements with selector: ${selector} in ${categoryName}`);
                
                if (elements.length > 0) {
                    for (let i = 0; i < Math.min(elements.length, 10); i++) {
                        const element = elements[i];
                        const productData = await this.extractProductFromElement(element, categoryName);
                        if (productData) {
                            this.data.products.push(productData);
                            console.log(`✅ Added product: ${productData.name}`);
                        }
                    }
                    break; // Found products with this selector, no need to try others
                }
            } catch (error) {
                console.log(`⚠️ Error with product selector ${selector}:`, error.message);
            }
        }
        
        // If no products found with containers, try individual product links
        if (this.data.products.filter(p => p.category === categoryName).length === 0) {
            await this.extractProductLinksFromCurrentPage(categoryName);
        }
    }

    async extractProductLinksFromCurrentPage(categoryName) {
        console.log(`🔗 Looking for individual product links in ${categoryName}...`);
        
        try {
            const productLinks = await this.page.$$('a[href*="/product/"]');
            console.log(`Found ${productLinks.length} product links in ${categoryName}`);
            
            for (let i = 0; i < Math.min(productLinks.length, 10); i++) {
                const link = productLinks[i];
                const href = await link.getAttribute('href');
                const text = await link.textContent();
                
                if (href && text && text.trim().length > 2) {
                    const productData = {
                        name: text.trim(),
                        slug: this.createSlug(text.trim()),
                        description: null,
                        price: 0,
                        image_url: null,
                        brand: null,
                        category: categoryName,
                        sku: null,
                        is_active: true,
                        stock_quantity: 0,
                        url: href.startsWith('http') ? href : `${this.baseUrl}${href}`
                    };
                    
                    this.data.products.push(productData);
                    console.log(`✅ Added product link: ${text.trim()}`);
                }
            }
        } catch (error) {
            console.log(`⚠️ Error extracting product links from ${categoryName}:`, error.message);
        }
    }

    async extractProductFromElement(element, categoryName) {
        try {
            // Try to find product name
            const nameSelectors = [
                '.product-name', '.product-title', '.title', '.name',
                'h1', 'h2', 'h3', 'h4', 'h5',
                'a[href*="/product/"]'
            ];
            
            let name = '';
            let url = '';
            
            for (const selector of nameSelectors) {
                const nameElement = await element.$(selector);
                if (nameElement) {
                    name = await nameElement.textContent();
                    if (nameElement.tagName === 'A') {
                        url = await nameElement.getAttribute('href') || '';
                    }
                    if (name && name.trim()) break;
                }
            }
            
            // Try to find price
            const priceSelectors = [
                '.price', '.product-price', '.cost', '[data-price]', '.amount'
            ];
            
            let price = '';
            for (const selector of priceSelectors) {
                const priceElement = await element.$(selector);
                if (priceElement) {
                    price = await priceElement.textContent();
                    if (price && price.trim()) break;
                }
            }
            
            // Try to find image
            const imageElement = await element.$('img');
            let imageUrl = '';
            if (imageElement) {
                imageUrl = await imageElement.getAttribute('src') || '';
            }
            
            // Extract numeric price
            const numericPrice = price ? parseFloat(price.replace(/[^\d.,]/g, '').replace(',', '.')) : 0;
            
            if (name && name.trim()) {
                return {
                    name: name.trim(),
                    slug: this.createSlug(name.trim()),
                    description: null,
                    price: numericPrice || 0,
                    image_url: imageUrl ? (imageUrl.startsWith('http') ? imageUrl : `${this.baseUrl}${imageUrl}`) : null,
                    brand: null,
                    category: categoryName,
                    sku: null,
                    is_active: true,
                    stock_quantity: 0,
                    url: url ? (url.startsWith('http') ? url : `${this.baseUrl}${url}`) : null
                };
            }
            
        } catch (error) {
            console.log('⚠️ Error in extractProductFromElement:', error.message);
        }
        
        return null;
    }

    async extractBrands() {
        console.log('🏷️ Looking for brands...');
        
        // Navigate back to homepage
        await this.page.goto(this.baseUrl, { waitUntil: 'domcontentloaded' });
        await this.page.waitForTimeout(2000);
        
        const brandSelectors = [
            'a[href*="/brand/"]',
            'a[href*="/marque/"]', 
            '.brand-link',
            '.brand-item'
        ];

        const brands = new Set();
        
        for (const selector of brandSelectors) {
            try {
                const elements = await this.page.$$(selector);
                
                for (const element of elements) {
                    let brandName = '';
                    let brandUrl = '';
                    let logoUrl = '';
                    
                    if (element.tagName === 'IMG') {
                        brandName = await element.getAttribute('alt') || '';
                        logoUrl = await element.getAttribute('src') || '';
                        const parent = await element.$('xpath=..');
                        if (parent) {
                            brandUrl = await parent.getAttribute('href') || '';
                        }
                    } else {
                        brandName = await element.textContent() || '';
                        brandUrl = await element.getAttribute('href') || '';
                        
                        const img = await element.$('img');
                        if (img) {
                            logoUrl = await img.getAttribute('src') || '';
                        }
                    }
                    
                    if (brandName.trim()) {
                        brands.add(JSON.stringify({
                            name: brandName.trim(),
                            slug: this.createSlug(brandName.trim()),
                            description: null,
                            logo_url: logoUrl ? (logoUrl.startsWith('http') ? logoUrl : `${this.baseUrl}${logoUrl}`) : null,
                            website_url: brandUrl ? (brandUrl.startsWith('http') ? brandUrl : `${this.baseUrl}${brandUrl}`) : null,
                            is_active: true,
                            sort_order: brands.size + 1
                        }));
                    }
                }
            } catch (error) {
                console.log(`⚠️ Error with brand selector ${selector}:`, error.message);
            }
        }

        this.data.brands = Array.from(brands).map(b => JSON.parse(b));
        console.log(`✅ Found ${this.data.brands.length} brands`);
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
        console.log('💾 Saving scraped data...');
        
        const outputDir = path.join(__dirname, 'scraped-data');
        await fs.ensureDir(outputDir);
        
        // Save individual files
        await fs.writeJSON(path.join(outputDir, 'brands-advanced.json'), this.data.brands, { spaces: 2 });
        await fs.writeJSON(path.join(outputDir, 'categories-advanced.json'), this.data.categories, { spaces: 2 });
        await fs.writeJSON(path.join(outputDir, 'products-advanced.json'), this.data.products, { spaces: 2 });
        
        // Save combined data
        await fs.writeJSON(path.join(outputDir, 'pafen-data-advanced.json'), this.data, { spaces: 2 });
        
        // Save summary
        const summary = {
            scraping_date: new Date().toISOString(),
            source_website: this.baseUrl,
            scraper_used: 'Advanced Playwright',
            total_brands: this.data.brands.length,
            total_categories: this.data.categories.length,
            total_products: this.data.products.length,
            brands_with_logos: this.data.brands.filter(b => b.logo_url).length,
            products_with_images: this.data.products.filter(p => p.image_url).length,
            products_with_prices: this.data.products.filter(p => p.price > 0).length
        };
        
        await fs.writeJSON(path.join(outputDir, 'scraping-summary-advanced.json'), summary, { spaces: 2 });
        
        console.log('✅ Data saved successfully');
        console.log(`📊 Summary: ${summary.total_brands} brands, ${summary.total_categories} categories, ${summary.total_products} products`);
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
            await this.scrapeCategoriesAndProducts();
            await this.saveData();
        } catch (error) {
            console.error('❌ Scraping failed:', error.message);
            throw error;
        } finally {
            await this.close();
        }
    }
}

// CLI execution
async function main() {
    const scraper = new AdvancedPafenScraper();
    
    try {
        await scraper.run();
        console.log('🎉 Advanced scraping completed successfully!');
    } catch (error) {
        console.error('💥 Advanced scraping failed:', error.message);
        process.exit(1);
    }
}

// Run if this file is executed directly
if (require.main === module) {
    main();
}

module.exports = AdvancedPafenScraper;