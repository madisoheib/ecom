const { chromium } = require('playwright');
const fs = require('fs-extra');
const path = require('path');

class CompletePafenScraper {
    constructor() {
        this.baseUrl = 'https://pafen-dz.com';
        this.browser = null;
        this.page = null;
        this.data = {
            brands: [],
            categories: [],
            products: []
        };
        this.productsByCategory = {};
        this.delay = 2000; // 2 seconds between requests
    }

    async init() {
        console.log('🚀 Initializing Complete Pafen scraper for ALL products...');
        
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

    async scrapeAllCategories() {
        console.log('🔍 Starting complete category scraping...');
        
        try {
            // Navigate to homepage to get categories
            await this.page.goto(this.baseUrl, { 
                waitUntil: 'domcontentloaded',
                timeout: 60000 
            });
            await this.page.waitForTimeout(3000);

            // Extract all categories
            await this.extractCategories();
            
            // Create products directory structure
            const productsDir = path.join(__dirname, 'products');
            await fs.ensureDir(productsDir);
            
            // Scrape each category completely
            for (const category of this.data.categories) {
                try {
                    console.log(`\n🏷️ Starting category: ${category.name}`);
                    console.log(`📂 URL: ${category.url}`);
                    
                    const categoryProducts = await this.scrapeFullCategory(category);
                    
                    // Save category products to individual folder
                    const categoryFileName = `${this.createSlug(category.name)}.json`;
                    const categoryPath = path.join(productsDir, categoryFileName);
                    
                    await fs.writeJSON(categoryPath, categoryProducts, { spaces: 2 });
                    console.log(`💾 Saved ${categoryProducts.length} products to products/${categoryFileName}`);
                    
                    this.productsByCategory[category.name] = categoryProducts;
                    this.data.products.push(...categoryProducts);
                    
                    // Delay between categories
                    console.log(`⏳ Waiting ${this.delay/1000}s before next category...`);
                    await this.page.waitForTimeout(this.delay);
                    
                } catch (error) {
                    console.error(`❌ Error scraping category ${category.name}:`, error.message);
                }
            }
            
        } catch (error) {
            console.error('❌ Complete scraping failed:', error.message);
            throw error;
        }
    }

    async extractCategories() {
        console.log('📂 Extracting all categories...');
        
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
        console.log(`✅ Found ${this.data.categories.length} unique categories:`);
        this.data.categories.forEach((cat, index) => {
            console.log(`   ${index + 1}. ${cat.name} (ID: ${cat.id})`);
        });
    }

    async scrapeFullCategory(category) {
        console.log(`🛍️ Scraping ALL pages of ${category.name}...`);
        
        const allProducts = [];
        let currentPage = 1;
        let hasMorePages = true;
        
        while (hasMorePages && currentPage <= 20) { // Safety limit of 20 pages
            try {
                // Construct page URL
                const pageUrl = currentPage === 1 ? 
                    category.url : 
                    `${category.url}?page=${currentPage}`;
                
                console.log(`📄 Scraping page ${currentPage}: ${pageUrl}`);
                
                // Navigate to page
                await this.page.goto(pageUrl, { 
                    waitUntil: 'domcontentloaded',
                    timeout: 30000 
                });
                await this.page.waitForTimeout(2000);
                
                // Get all product URLs from this page
                const productUrls = await this.getProductUrlsFromPage();
                console.log(`🔗 Found ${productUrls.length} product URLs on page ${currentPage}`);
                
                if (productUrls.length === 0) {
                    console.log(`❌ No products found on page ${currentPage}, ending pagination`);
                    hasMorePages = false;
                    break;
                }
                
                // Scrape each product on this page
                for (let i = 0; i < productUrls.length; i++) {
                    try {
                        const productUrl = productUrls[i];
                        console.log(`   🔍 [${i + 1}/${productUrls.length}] Scraping: ${productUrl}`);
                        
                        const productData = await this.scrapeProductDetails(productUrl, category.name);
                        if (productData) {
                            allProducts.push(productData);
                            console.log(`   ✅ Added: ${productData.title} - ${productData.price} DZD`);
                        }
                        
                        // Small delay between products
                        await this.page.waitForTimeout(500);
                        
                    } catch (error) {
                        console.log(`   ⚠️ Error scraping product: ${error.message}`);
                    }
                }
                
                // Check if there's a next page by trying the next page URL
                currentPage++;
                const nextPageUrl = `${category.url}?page=${currentPage}`;
                
                try {
                    await this.page.goto(nextPageUrl, { 
                        waitUntil: 'domcontentloaded',
                        timeout: 15000 
                    });
                    await this.page.waitForTimeout(1000);
                    
                    const nextPageProducts = await this.getProductUrlsFromPage();
                    if (nextPageProducts.length === 0) {
                        console.log(`📄 Page ${currentPage} has no products, stopping pagination`);
                        hasMorePages = false;
                    } else {
                        // Go back to continue the loop
                        currentPage--; // Will be incremented at start of loop
                        console.log(`📄 Page ${currentPage + 1} has ${nextPageProducts.length} products, continuing...`);
                    }
                } catch (error) {
                    console.log(`📄 Page ${currentPage} not accessible, stopping pagination`);
                    hasMorePages = false;
                }
                
            } catch (error) {
                console.log(`⚠️ Error on page ${currentPage} of ${category.name}:`, error.message);
                hasMorePages = false;
            }
        }
        
        console.log(`✅ Completed ${category.name}: ${allProducts.length} total products from ${currentPage - 1} pages`);
        return allProducts;
    }

    async getProductUrlsFromPage() {
        try {
            const productLinks = await this.page.$$('a[href*="/product/"]');
            const urls = [];
            
            for (const link of productLinks) {
                const href = await link.getAttribute('href');
                if (href) {
                    const fullUrl = href.startsWith('http') ? href : `${this.baseUrl}${href}`;
                    if (!urls.includes(fullUrl)) {
                        urls.push(fullUrl);
                    }
                }
            }
            
            return urls;
        } catch (error) {
            console.log(`⚠️ Error getting product URLs:`, error.message);
            return [];
        }
    }

    async scrapeProductDetails(productUrl, categoryName) {
        try {
            await this.page.goto(productUrl, { 
                waitUntil: 'domcontentloaded',
                timeout: 30000 
            });
            await this.page.waitForTimeout(1000);
            
            // Extract product title
            const titleSelectors = [
                'h1', 'h2', '.product-title', '.product-name', '[itemprop="name"]'
            ];
            
            let title = '';
            for (const selector of titleSelectors) {
                try {
                    const element = await this.page.$(selector);
                    if (element) {
                        const text = await element.textContent();
                        if (text && text.trim().length > 0) {
                            title = text.trim();
                            break;
                        }
                    }
                } catch (e) {}
            }
            
            // Extract price looking for DZD
            let price = 0;
            let priceText = '';
            
            try {
                // Look for any element containing DZD
                await this.page.waitForTimeout(500);
                const priceElement = await this.page.$('*:has-text("DZD")');
                if (priceElement) {
                    priceText = await priceElement.textContent();
                    const match = priceText.match(/([\d\s,]+)(?=\s*DZD)/);
                    if (match) {
                        const numericString = match[1].replace(/\s/g, '').replace(',', '.');
                        price = parseFloat(numericString) || 0;
                    }
                }
            } catch (e) {
                // Alternative method: search all text content
                try {
                    const bodyText = await this.page.textContent('body');
                    const priceMatch = bodyText.match(/([\d\s,]+)\s*DZD/);
                    if (priceMatch) {
                        priceText = priceMatch[0];
                        const numericString = priceMatch[1].replace(/\s/g, '').replace(',', '.');
                        price = parseFloat(numericString) || 0;
                    }
                } catch (e2) {}
            }
            
            // Extract description
            const descSelectors = [
                '.product-description', '.description', '[itemprop="description"]'
            ];
            
            let content = '';
            for (const selector of descSelectors) {
                try {
                    const element = await this.page.$(selector);
                    if (element) {
                        const text = await element.textContent();
                        if (text && text.trim().length > 0) {
                            content = text.trim().slice(0, 500);
                            break;
                        }
                    }
                } catch (e) {}
            }
            
            // Extract image
            let imageUrl = '';
            try {
                const imgElement = await this.page.$('.product-image img, img[itemprop="image"]');
                if (imgElement) {
                    const src = await imgElement.getAttribute('src');
                    if (src && !src.includes('placeholder')) {
                        imageUrl = src.startsWith('http') ? src : `${this.baseUrl}${src}`;
                    }
                }
            } catch (e) {}

            // Extract brand
            const brand = this.extractBrandFromTitle(title);
            
            if (title) {
                return {
                    title: title,
                    name: title,
                    slug: this.createSlug(title),
                    content: content || null,
                    description: content || null,
                    price: price,
                    price_text: priceText,
                    currency: 'DZD',
                    category: categoryName,
                    marque: brand,
                    brand: brand,
                    image_url: imageUrl || null,
                    sku: this.extractSkuFromUrl(productUrl),
                    is_active: true,
                    stock_quantity: 0,
                    url: productUrl,
                    scraped_at: new Date().toISOString()
                };
            }
            
        } catch (error) {
            console.log(`⚠️ Error scraping product details: ${error.message}`);
        }
        
        return null;
    }

    extractBrandFromTitle(title) {
        const commonBrands = [
            'DIOR', 'CHANEL', 'YSL', 'YVES SAINT LAURENT', 'GUERLAIN',
            'LANCOME', 'VERSACE', 'ARMANI', 'CALVIN KLEIN', 'HUGO BOSS',
            'PACO RABANNE', 'JEAN PAUL GAULTIER', 'THIERRY MUGLER',
            'NISHANE', 'CREED', 'TOM FORD', 'BURBERRY', 'DOLCE GABBANA',
            'GIVENCHY', 'KENZO', 'ISSEY MIYAKE', 'RALPH LAUREN',
            'XERJOFF', 'AMOUAGE', 'MORESQUE', 'PARFUMS DE MARLY',
            'BLEND OUD', 'JPG', 'JEAN PAUL GAULTIER', 'VIKTOR', 'ROLF'
        ];
        
        const titleUpper = title.toUpperCase();
        for (const brand of commonBrands) {
            if (titleUpper.includes(brand)) {
                return brand;
            }
        }
        
        // Try to extract first word as brand
        const firstWord = title.split(' ')[0];
        if (firstWord.length > 2) {
            return firstWord.toUpperCase();
        }
        
        return null;
    }

    extractSkuFromUrl(url) {
        const match = url.match(/\/product\/(\d+)-/);
        return match ? match[1] : null;
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
        
        // Extract brands from all products
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
        
        // Save main files
        await fs.writeJSON(path.join(outputDir, 'brands-complete.json'), this.data.brands, { spaces: 2 });
        await fs.writeJSON(path.join(outputDir, 'categories-complete.json'), this.data.categories, { spaces: 2 });
        await fs.writeJSON(path.join(outputDir, 'products-complete.json'), this.data.products, { spaces: 2 });
        
        // Save combined data
        await fs.writeJSON(path.join(outputDir, 'pafen-data-complete.json'), {
            ...this.data,
            productsByCategory: this.productsByCategory
        }, { spaces: 2 });
        
        // Save summary
        const summary = {
            scraping_date: new Date().toISOString(),
            source_website: this.baseUrl,
            scraper_used: 'Complete Category Scraper with Full Pagination',
            total_brands: this.data.brands.length,
            total_categories: this.data.categories.length,
            total_products: this.data.products.length,
            brands_with_logos: this.data.brands.filter(b => b.logo_url).length,
            products_with_images: this.data.products.filter(p => p.image_url).length,
            products_with_prices: this.data.products.filter(p => p.price > 0).length,
            average_price: this.data.products.length > 0 ? 
                this.data.products.reduce((sum, p) => sum + p.price, 0) / this.data.products.length : 0,
            price_range: this.data.products.length > 0 ? {
                min: Math.min(...this.data.products.map(p => p.price)),
                max: Math.max(...this.data.products.map(p => p.price))
            } : { min: 0, max: 0 },
            products_by_category: Object.fromEntries(
                Object.entries(this.productsByCategory).map(([cat, products]) => [cat, products.length])
            )
        };
        
        await fs.writeJSON(path.join(outputDir, 'scraping-summary-complete.json'), summary, { spaces: 2 });
        
        console.log('\n🎉 Complete scraping finished!');
        console.log(`📊 Summary: ${summary.total_brands} brands, ${summary.total_categories} categories, ${summary.total_products} products`);
        console.log(`💰 Price info: ${summary.products_with_prices} products with prices, avg: ${summary.average_price.toFixed(2)} DZD`);
        if (summary.total_products > 0) {
            console.log(`📈 Price range: ${summary.price_range.min} - ${summary.price_range.max} DZD`);
        }
        
        console.log('\n📂 Products by category:');
        for (const [category, count] of Object.entries(summary.products_by_category)) {
            console.log(`   ${category}: ${count} products`);
        }
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
            await this.scrapeAllCategories();
            await this.saveData();
        } catch (error) {
            console.error('❌ Complete scraping failed:', error.message);
            throw error;
        } finally {
            await this.close();
        }
    }
}

// CLI execution
async function main() {
    const scraper = new CompletePafenScraper();
    
    try {
        await scraper.run();
        console.log('🎉 Complete scraping with full pagination completed successfully!');
    } catch (error) {
        console.error('💥 Complete scraping failed:', error.message);
        process.exit(1);
    }
}

// Run if this file is executed directly
if (require.main === module) {
    main();
}

module.exports = CompletePafenScraper;