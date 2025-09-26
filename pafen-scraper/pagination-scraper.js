const { chromium } = require('playwright');
const fs = require('fs-extra');
const path = require('path');

class PaginationPafenScraper {
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
        this.scrapedUrls = new Set();
    }

    async init() {
        console.log('🚀 Initializing Pagination Pafen scraper with complete category extraction...');
        
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

    async scrapeWithPagination() {
        console.log('🔍 Starting comprehensive scraping with pagination support...');
        
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
            
            // Scrape each category with pagination
            await this.scrapeAllCategoriesWithPagination();
            
            // Extract brands from products
            await this.extractBrandsFromProducts();
            
        } catch (error) {
            console.error('❌ Comprehensive scraping failed:', error.message);
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

    async scrapeAllCategoriesWithPagination() {
        console.log('🛍️ Scraping ALL categories with pagination...');
        
        for (const category of this.data.categories) {
            try {
                console.log(`\n📖 Processing category: ${category.name}...`);
                this.productsByCategory[category.name] = [];
                
                await this.scrapeCategoryWithPagination(category);
                
                console.log(`✅ Completed ${category.name}: ${this.productsByCategory[category.name].length} products`);
                
            } catch (error) {
                console.log(`⚠️ Error scraping category ${category.name}:`, error.message);
            }
        }
    }

    async scrapeCategoryWithPagination(category) {
        let currentPage = 1;
        let hasNextPage = true;
        
        while (hasNextPage && currentPage <= 10) { // Limit to 10 pages per category
            try {
                const pageUrl = currentPage === 1 ? category.url : `${category.url}?page=${currentPage}`;
                console.log(`📄 Scraping page ${currentPage} of ${category.name}...`);
                
                await this.page.goto(pageUrl, { 
                    waitUntil: 'domcontentloaded',
                    timeout: 30000 
                });
                await this.page.waitForTimeout(2000);
                
                // Check if this page has products
                const productLinks = await this.page.$$('a[href*="/product/"]');
                console.log(`Found ${productLinks.length} product links on page ${currentPage}`);
                
                if (productLinks.length === 0) {
                    console.log(`❌ No products found on page ${currentPage}, ending pagination`);
                    hasNextPage = false;
                    break;
                }
                
                // Extract products from current page (limit 20 per page for testing)
                const pageProducts = [];
                for (let i = 0; i < Math.min(productLinks.length, 20); i++) {
                    const link = productLinks[i];
                    const href = await link.getAttribute('href');
                    
                    if (href) {
                        const fullUrl = href.startsWith('http') ? href : `${this.baseUrl}${href}`;
                        
                        if (!this.scrapedUrls.has(fullUrl)) {
                            this.scrapedUrls.add(fullUrl);
                            
                            try {
                                const productData = await this.scrapeProductDetails(fullUrl, category.name);
                                if (productData) {
                                    pageProducts.push(productData);
                                    this.productsByCategory[category.name].push(productData);
                                    this.data.products.push(productData);
                                    console.log(`✅ Added: ${productData.name} - ${productData.price} DZD`);
                                }
                            } catch (error) {
                                console.log(`⚠️ Error scraping product ${fullUrl}:`, error.message);
                            }
                            
                            // Small delay between products
                            await this.page.waitForTimeout(500);
                        }
                    }
                }
                
                // Check for next page
                hasNextPage = await this.checkForNextPage();
                currentPage++;
                
            } catch (error) {
                console.log(`⚠️ Error on page ${currentPage} of ${category.name}:`, error.message);
                hasNextPage = false;
            }
        }
    }

    async checkForNextPage() {
        try {
            // Look for pagination elements
            const paginationSelectors = [
                'a[rel="next"]',
                '.pagination .next',
                '.pagination a:contains("Next")',
                '.pagination a:contains("Suivant")',
                '.page-numbers.next',
                'a.next-page'
            ];
            
            for (const selector of paginationSelectors) {
                const nextButton = await this.page.$(selector);
                if (nextButton) {
                    console.log(`🔄 Found next page button: ${selector}`);
                    return true;
                }
            }
            
            // Alternative: check if current page has fewer products than expected
            const productCount = await this.page.$$eval('a[href*="/product/"]', elements => elements.length);
            if (productCount < 20) { // Assuming normal page has 20+ products
                console.log(`📄 Page has only ${productCount} products, likely last page`);
                return false;
            }
            
            return false;
        } catch (error) {
            console.log(`⚠️ Error checking for next page:`, error.message);
            return false;
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
                'h1', 'h2',
                '.product-title',
                '.product-name',
                '[itemprop="name"]',
                '.page-title'
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
            
            // Extract price with better selectors
            let price = 0;
            let priceText = '';
            
            try {
                // Look for text containing DZD
                const allElements = await this.page.$$('*');
                for (const element of allElements) {
                    const text = await element.textContent();
                    if (text && text.includes('DZD') && text.match(/[\d\s,]+\s*DZD/)) {
                        priceText = text.trim();
                        const match = priceText.match(/([\d\s,]+)(?=\s*DZD)/);
                        if (match) {
                            const numericString = match[1].replace(/\s/g, '').replace(',', '.');
                            const parsedPrice = parseFloat(numericString);
                            if (parsedPrice > 0) {
                                price = parsedPrice;
                                break;
                            }
                        }
                    }
                }
            } catch (e) {}
            
            // Extract description/content
            const contentSelectors = [
                '.product-description',
                '.description',
                '[itemprop="description"]',
                '.product-details',
                '.product-content'
            ];
            
            let content = '';
            for (const selector of contentSelectors) {
                try {
                    const element = await this.page.$(selector);
                    if (element) {
                        const text = await element.textContent();
                        if (text && text.trim().length > 0) {
                            content = text.trim().slice(0, 1000); // Limit content length
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
                '.main-image img',
                '.product-photo img'
            ];
            
            let imageUrl = '';
            for (const selector of imgSelectors) {
                try {
                    const element = await this.page.$(selector);
                    if (element) {
                        const src = await element.getAttribute('src');
                        if (src && !src.includes('placeholder')) {
                            imageUrl = src.startsWith('http') ? src : `${this.baseUrl}${src}`;
                            break;
                        }
                    }
                } catch (e) {}
            }

            // Extract brand (marque)
            const brand = this.extractBrandFromTitle(title);
            
            if (title) {
                return {
                    title: title,
                    name: title, // Keep both for compatibility
                    slug: this.createSlug(title),
                    content: content || null,
                    description: content || null,
                    price: price,
                    price_text: priceText,
                    currency: 'DZD',
                    category: categoryName,
                    marque: brand, // French for brand
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
            console.log(`⚠️ Error scraping product details from ${productUrl}:`, error.message);
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
            'BLEND OUD', 'JPG', 'JEAN PAUL GAULTIER'
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
        console.log('💾 Saving organized product data...');
        
        const outputDir = path.join(__dirname, 'scraped-data');
        const productsDir = path.join(__dirname, 'products');
        await fs.ensureDir(outputDir);
        await fs.ensureDir(productsDir);
        
        // Save main files
        await fs.writeJSON(path.join(outputDir, 'brands-pagination.json'), this.data.brands, { spaces: 2 });
        await fs.writeJSON(path.join(outputDir, 'categories-pagination.json'), this.data.categories, { spaces: 2 });
        await fs.writeJSON(path.join(outputDir, 'products-pagination.json'), this.data.products, { spaces: 2 });
        
        // Save products by category in products folder
        for (const [categoryName, products] of Object.entries(this.productsByCategory)) {
            const fileName = `${this.createSlug(categoryName)}.json`;
            await fs.writeJSON(path.join(productsDir, fileName), products, { spaces: 2 });
            console.log(`📁 Saved ${products.length} products to products/${fileName}`);
        }
        
        // Save combined data
        await fs.writeJSON(path.join(outputDir, 'pafen-data-pagination.json'), {
            ...this.data,
            productsByCategory: this.productsByCategory
        }, { spaces: 2 });
        
        // Save comprehensive summary
        const summary = {
            scraping_date: new Date().toISOString(),
            source_website: this.baseUrl,
            scraper_used: 'Pagination Playwright with Category Organization',
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
            },
            products_by_category: Object.fromEntries(
                Object.entries(this.productsByCategory).map(([cat, products]) => [cat, products.length])
            )
        };
        
        await fs.writeJSON(path.join(outputDir, 'scraping-summary-pagination.json'), summary, { spaces: 2 });
        
        console.log('✅ Data saved successfully');
        console.log(`📊 Summary: ${summary.total_brands} brands, ${summary.total_categories} categories, ${summary.total_products} products`);
        console.log(`💰 Price info: ${summary.products_with_prices} products with prices, avg: ${summary.average_price.toFixed(2)} DZD`);
        console.log(`📈 Price range: ${summary.price_range.min} - ${summary.price_range.max} DZD`);
        
        // Show category breakdown
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
            await this.scrapeWithPagination();
            await this.saveData();
        } catch (error) {
            console.error('❌ Pagination scraping failed:', error.message);
            throw error;
        } finally {
            await this.close();
        }
    }
}

// CLI execution
async function main() {
    const scraper = new PaginationPafenScraper();
    
    try {
        await scraper.run();
        console.log('🎉 Pagination scraping with category organization completed successfully!');
    } catch (error) {
        console.error('💥 Pagination scraping failed:', error.message);
        process.exit(1);
    }
}

// Run if this file is executed directly
if (require.main === module) {
    main();
}

module.exports = PaginationPafenScraper;