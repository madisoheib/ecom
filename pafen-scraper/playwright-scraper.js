const { chromium } = require('playwright');
const fs = require('fs-extra');
const path = require('path');

class PafenPlaywrightScraper {
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
        console.log('🚀 Initializing Playwright Pafen scraper...');
        
        try {
            this.browser = await chromium.launch({
                headless: false, // Set to true for production
                slowMo: 100 // Add slight delay between actions
            });
            
            this.page = await this.browser.newPage();
            
            // Set user agent and viewport
            await this.page.setExtraHTTPHeaders({
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            });
            await this.page.setViewportSize({ width: 1366, height: 768 });
            
            console.log('✅ Playwright browser initialized successfully');
            
        } catch (error) {
            console.error('❌ Browser initialization failed:', error.message);
            throw error;
        }
    }

    async exploreSite() {
        console.log('🔍 Exploring site structure...');
        
        try {
            // Navigate to homepage with increased timeout and less strict waiting
            console.log(`🌐 Navigating to ${this.baseUrl}...`);
            await this.page.goto(this.baseUrl, { 
                waitUntil: 'domcontentloaded',
                timeout: 60000 
            });
            await this.page.waitForTimeout(3000);
            console.log('✅ Page loaded successfully');

            // Take screenshot for debugging
            await this.page.screenshot({ path: 'debug-homepage-playwright.png', fullPage: true });
            
            // Find navigation and menu elements
            const navSelectors = [
                'nav',
                '.navbar',
                '.menu',
                '.navigation',
                '.header-menu',
                '.main-menu',
                '[role="navigation"]',
                '.nav-menu'
            ];

            let foundNavigation = false;
            for (const selector of navSelectors) {
                const element = await this.page.$(selector);
                if (element) {
                    console.log(`✅ Found navigation element: ${selector}`);
                    foundNavigation = true;
                    break;
                }
            }

            if (!foundNavigation) {
                console.log('⚠️ No standard navigation found, exploring page structure...');
            }

            // Look for category links
            await this.findCategories();
            
            // Look for brand information
            await this.findBrands();
            
            // Look for products
            await this.findProducts();
            
        } catch (error) {
            console.error('❌ Site exploration failed:', error.message);
            throw error;
        }
    }

    async findCategories() {
        console.log('📂 Looking for categories...');
        
        const categorySelectors = [
            'a[href*="categorie"]',
            'a[href*="category"]',
            'a[href*="produit"]',
            'a[href*="product"]',
            '.category-link',
            '.nav-item a',
            '.menu-item a',
            'nav a',
            '.dropdown-menu a'
        ];

        const categories = new Set();
        
        for (const selector of categorySelectors) {
            try {
                const elements = await this.page.$$(selector);
                
                for (const element of elements) {
                    const href = await element.getAttribute('href');
                    const text = await element.textContent();
                    
                    if (href && text && text.trim().length > 0) {
                        // Filter out non-category links
                        const cleanText = text.trim();
                        if (!this.isNonCategoryLink(cleanText, href)) {
                            categories.add({
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
                console.log(`⚠️ Error with selector ${selector}:`, error.message);
            }
        }

        this.data.categories = Array.from(categories);
        console.log(`✅ Found ${this.data.categories.length} categories`);
    }

    async findBrands() {
        console.log('🏷️ Looking for brands...');
        
        const brandSelectors = [
            'a[href*="marque"]',
            'a[href*="brand"]',
            '.brand-link',
            '.brand-item',
            'img[alt*="marque"]',
            'img[alt*="brand"]'
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
                        
                        // Look for logo within the link
                        const img = await element.$('img');
                        if (img) {
                            logoUrl = await img.getAttribute('src') || '';
                        }
                    }
                    
                    if (brandName.trim()) {
                        brands.add({
                            name: brandName.trim(),
                            slug: this.createSlug(brandName.trim()),
                            description: null,
                            logo_url: logoUrl ? (logoUrl.startsWith('http') ? logoUrl : `${this.baseUrl}${logoUrl}`) : null,
                            website_url: brandUrl ? (brandUrl.startsWith('http') ? brandUrl : `${this.baseUrl}${brandUrl}`) : null,
                            is_active: true,
                            sort_order: brands.size + 1
                        });
                    }
                }
            } catch (error) {
                console.log(`⚠️ Error with brand selector ${selector}:`, error.message);
            }
        }

        this.data.brands = Array.from(brands);
        console.log(`✅ Found ${this.data.brands.length} brands`);
    }

    async findProducts() {
        console.log('🛍️ Looking for products...');
        
        const productSelectors = [
            '.product-item',
            '.product-card',
            '.product',
            '[data-product]',
            '.item-product',
            'a[href*="produit"]',
            'a[href*="product"]'
        ];

        const products = new Set();
        
        for (const selector of productSelectors) {
            try {
                const elements = await this.page.$$(selector);
                console.log(`Found ${elements.length} elements with selector: ${selector}`);
                
                for (let i = 0; i < Math.min(elements.length, 20); i++) { // Limit to first 20
                    const element = elements[i];
                    
                    try {
                        // For product links, get basic info directly
                        if (selector.includes('href*="product"')) {
                            const href = await element.getAttribute('href');
                            const text = await element.textContent();
                            
                            console.log(`Found product link: "${text?.trim()}" -> ${href}`);
                            
                            if (href && text && text.trim().length > 0 && !this.isNonProductLink(text.trim(), href)) {
                                const productData = {
                                    name: text.trim(),
                                    slug: this.createSlug(text.trim()),
                                    description: null,
                                    price: 0,
                                    image_url: null,
                                    brand: null,
                                    sku: null,
                                    is_active: true,
                                    stock_quantity: 0,
                                    url: href.startsWith('http') ? href : `${this.baseUrl}${href}`
                                };
                                products.add(JSON.stringify(productData)); // Use string to avoid duplicates
                                console.log(`✅ Added product: ${text.trim()}`);
                            }
                        } else {
                            // Extract product information from product containers
                            const productData = await this.extractProductData(element);
                            if (productData && productData.name) {
                                products.add(JSON.stringify(productData));
                            }
                        }
                    } catch (error) {
                        console.log(`⚠️ Error extracting product data:`, error.message);
                    }
                }
                
                if (elements.length > 0) break; // Stop after finding products with one selector
            } catch (error) {
                console.log(`⚠️ Error with product selector ${selector}:`, error.message);
            }
        }

        this.data.products = Array.from(products).map(p => JSON.parse(p));
        console.log(`✅ Found ${this.data.products.length} products`);
    }

    async extractProductData(element) {
        try {
            // Try to find product name
            const nameSelectors = [
                '.product-name',
                '.product-title',
                'h2', 'h3', 'h4',
                '.title',
                '.name',
                'a[href*="produit"]',
                'a[href*="product"]'
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
                '.price',
                '.product-price',
                '.cost',
                '[data-price]',
                '.amount'
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
                    sku: null,
                    is_active: true,
                    stock_quantity: 0,
                    url: url ? (url.startsWith('http') ? url : `${this.baseUrl}${url}`) : null
                };
            }
            
        } catch (error) {
            console.log('⚠️ Error in extractProductData:', error.message);
        }
        
        return null;
    }

    isNonCategoryLink(text, href) {
        const excludeTexts = [
            'mon compte', 'account', 'login', 'register',
            'français', 'english', 'عربي',
            'contact', 'about', 'aide', 'help',
            'panier', 'cart', 'checkout',
            'search', 'recherche'
        ];
        
        const textLower = text.toLowerCase();
        return excludeTexts.some(exclude => textLower.includes(exclude));
    }

    isNonProductLink(text, href) {
        const excludeTexts = [
            'accueil', 'home', 'login', 'register', 'contact',
            'about', 'à propos', 'boutique', 'shop', 'panier',
            'cart', 'mon compte', 'account', 'search', 'recherche',
            's\'identifier', 's\'inscrire', 'achetez maintenant'
        ];
        
        const textLower = text.toLowerCase();
        return excludeTexts.some(exclude => textLower.includes(exclude)) || 
               text.trim().length < 3 || 
               href.includes('/login') || 
               href.includes('/register') ||
               href.includes('/about') ||
               href.includes('/shop') ||
               href.includes('/cart');
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
        await fs.writeJSON(path.join(outputDir, 'brands-playwright.json'), this.data.brands, { spaces: 2 });
        await fs.writeJSON(path.join(outputDir, 'categories-playwright.json'), this.data.categories, { spaces: 2 });
        await fs.writeJSON(path.join(outputDir, 'products-playwright.json'), this.data.products, { spaces: 2 });
        
        // Save combined data
        await fs.writeJSON(path.join(outputDir, 'pafen-data-playwright.json'), this.data, { spaces: 2 });
        
        // Save summary
        const summary = {
            scraping_date: new Date().toISOString(),
            source_website: this.baseUrl,
            scraper_used: 'Playwright',
            total_brands: this.data.brands.length,
            total_categories: this.data.categories.length,
            total_products: this.data.products.length,
            brands_with_logos: this.data.brands.filter(b => b.logo_url).length,
            products_with_images: this.data.products.filter(p => p.image_url).length
        };
        
        await fs.writeJSON(path.join(outputDir, 'scraping-summary-playwright.json'), summary, { spaces: 2 });
        
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
            await this.exploreSite();
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
    const scraper = new PafenPlaywrightScraper();
    
    try {
        await scraper.run();
        console.log('🎉 Scraping completed successfully!');
    } catch (error) {
        console.error('💥 Scraping failed:', error.message);
        process.exit(1);
    }
}

// Run if this file is executed directly
if (require.main === module) {
    main();
}

module.exports = PafenPlaywrightScraper;