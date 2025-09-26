const puppeteer = require('puppeteer');
const fs = require('fs-extra');
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
        
        try {
            this.browser = await puppeteer.launch({
                headless: true,
                args: ['--no-sandbox', '--disable-setuid-sandbox']
            });
            
            console.log('✅ Browser launched successfully');
            
            this.page = await this.browser.newPage();
            console.log('✅ Page created successfully');
            
            // Set user agent to avoid blocking
            await this.page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            
            // Set viewport
            await this.page.setViewport({ width: 1366, height: 768 });
            
            console.log('✅ Browser initialized successfully');
            
        } catch (error) {
            console.error('❌ Browser initialization failed:', error.message);
            throw error;
        }
    }

    async scrapeBrands() {
        console.log('📋 Starting brands scraping...');
        
        try {
            // Navigate to the main page first
            await this.page.goto(this.baseUrl, { 
                waitUntil: 'networkidle2',
                timeout: 30000 
            });
            
            console.log('🔍 Looking for brands section...');
            
            // Wait for page to load and take a screenshot for debugging
            await this.page.screenshot({ path: 'debug-homepage.png' });
            
            // Try to find brands/marques in navigation or footer
            const brandsSelectors = [
                'a[href*="marque"]',
                'a[href*="brand"]',
                '.brands a',
                '.marques a',
                'nav a[href*="marque"]',
                '.menu a[href*="marque"]',
                '.footer a[href*="marque"]',
                'a:contains("Marques")',
                'a:contains("Brands")'
            ];
            
            let brandsFound = false;
            let brandLinks = [];
            
            // First, try to find a dedicated brands page
            for (const selector of brandsSelectors) {
                try {
                    const elements = await this.page.$$(selector);
                    if (elements.length > 0) {
                        console.log(`📍 Found potential brands link with selector: ${selector}`);
                        
                        // Click the first brands link
                        await elements[0].click();
                        await this.page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 10000 });
                        
                        console.log(`🔗 Navigated to: ${this.page.url()}`);
                        
                        // Now try to scrape brands from this page
                        brandLinks = await this.scrapeBrandsFromPage();
                        
                        if (brandLinks.length > 0) {
                            brandsFound = true;
                            break;
                        }
                    }
                } catch (e) {
                    console.log(`❌ Selector ${selector} failed: ${e.message}`);
                    // Go back to homepage and try next selector
                    await this.page.goto(this.baseUrl, { waitUntil: 'networkidle2' });
                }
            }
            
            // If no dedicated brands page found, try to extract brands from products
            if (!brandsFound) {
                console.log('🔍 No dedicated brands page found, extracting from products...');
                await this.page.goto(this.baseUrl, { waitUntil: 'networkidle2' });
                brandLinks = await this.discoverBrandsFromProducts();
            }
            
            // Process each brand to get logos and additional info
            if (brandLinks.length > 0) {
                console.log(`🔄 Processing ${brandLinks.length} brands...`);
                
                for (let i = 0; i < brandLinks.length; i++) {
                    const brand = brandLinks[i];
                    console.log(`📦 Processing brand ${i + 1}/${brandLinks.length}: ${brand.name}`);
                    
                    try {
                        // If brand has a URL, visit it to get more info
                        if (brand.url && brand.url !== this.baseUrl) {
                            await this.page.goto(brand.url, { 
                                waitUntil: 'networkidle2',
                                timeout: 15000 
                            });
                            
                            // Look for brand logo
                            const logo = await this.findBrandLogo();
                            if (logo) {
                                brand.logo = logo;
                                console.log(`✅ Found logo for ${brand.name}`);
                            }
                            
                            // Look for brand description
                            const description = await this.findBrandDescription();
                            if (description) {
                                brand.description = description;
                            }
                        }
                        
                        this.data.brands.push({
                            name: brand.name,
                            slug: this.createSlug(brand.name),
                            description: brand.description || null,
                            logo_url: brand.logo || null,
                            website_url: brand.url || null,
                            is_active: true,
                            sort_order: i + 1
                        });
                        
                    } catch (error) {
                        console.log(`❌ Error processing brand ${brand.name}: ${error.message}`);
                        
                        // Add brand without additional info
                        this.data.brands.push({
                            name: brand.name,
                            slug: this.createSlug(brand.name),
                            description: null,
                            logo_url: null,
                            website_url: brand.url || null,
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
        
        // Take a screenshot for debugging
        await this.page.screenshot({ path: 'debug-brands-page.png' });
        
        const brandSelectors = [
            '.brand-item',
            '.marque-item', 
            '.brand-card',
            '.brand-link',
            '.brands-grid .item',
            '.marques-grid .item',
            '.brand-list li',
            '.brands-list li',
            '.brand-container',
            '.marques-container',
            '[class*="brand"]',
            '[class*="marque"]'
        ];
        
        for (const selector of brandSelectors) {
            try {
                const brands = await this.page.$$eval(selector, items => {
                    return items.map(item => {
                        const nameEl = item.querySelector('h1, h2, h3, h4, .name, .title, a') || item;
                        const linkEl = item.querySelector('a') || item;
                        const logoEl = item.querySelector('img');
                        
                        const name = nameEl.textContent ? nameEl.textContent.trim() : '';
                        
                        if (name && name.length > 1) {
                            return {
                                name: name,
                                url: linkEl.href || null,
                                logo: logoEl ? logoEl.src : null
                            };
                        }
                        return null;
                    }).filter(brand => brand !== null);
                });
                
                if (brands.length > 0) {
                    console.log(`✅ Found ${brands.length} brands using selector: ${selector}`);
                    return brands;
                }
            } catch (e) {
                console.log(`❌ Selector ${selector} failed: ${e.message}`);
            }
        }
        
        // If no structured brands found, try to find brand names in text
        try {
            console.log('🔍 Looking for brand names in page text...');
            const pageText = await this.page.evaluate(() => document.body.innerText);
            const brandNames = this.extractBrandNamesFromText(pageText);
            
            if (brandNames.length > 0) {
                console.log(`✅ Extracted ${brandNames.length} brand names from text`);
                return brandNames.map(name => ({
                    name: name,
                    url: null,
                    logo: null
                }));
            }
        } catch (e) {
            console.log(`❌ Text extraction failed: ${e.message}`);
        }
        
        return [];
    }

    async findBrandLogo() {
        const logoSelectors = [
            '.brand-logo img',
            '.marque-logo img',
            '.brand-header img',
            '.logo img',
            'img[alt*="logo"]',
            'img[src*="logo"]',
            'img[src*="brand"]',
            'img[src*="marque"]',
            '.brand img',
            '.marque img'
        ];
        
        for (const selector of logoSelectors) {
            try {
                const logo = await this.page.$eval(selector, img => img.src);
                if (logo) {
                    return logo;
                }
            } catch (e) {
                // Continue to next selector
            }
        }
        
        return null;
    }

    async findBrandDescription() {
        const descSelectors = [
            '.brand-description',
            '.marque-description',
            '.brand-info',
            '.brand-content p',
            '.description',
            '.about-brand'
        ];
        
        for (const selector of descSelectors) {
            try {
                const desc = await this.page.$eval(selector, el => el.textContent.trim());
                if (desc && desc.length > 10) {
                    return desc;
                }
            } catch (e) {
                // Continue to next selector
            }
        }
        
        return null;
    }

    async discoverBrandsFromProducts() {
        console.log('🔍 Discovering brands from products and site structure...');
        
        const brands = new Set();
        
        // First, add the site's own brand
        brands.add('Pafen');
        
        // Check if there's a brands/marques page in the site
        try {
            // Look for brands in the main navigation or footer
            const navigationBrands = await this.page.$$eval('a', links => {
                return links
                    .filter(link => 
                        link.textContent && 
                        (link.textContent.toLowerCase().includes('marque') || 
                         link.textContent.toLowerCase().includes('brand'))
                    )
                    .map(link => ({
                        text: link.textContent.trim(),
                        href: link.href
                    }));
            });
            
            console.log(`📍 Found ${navigationBrands.length} potential brand navigation items`);
            
            // Check for brands mentioned in product descriptions or text
            const pageText = await this.page.evaluate(() => document.body.innerText);
            const foundBrands = this.extractBrandNamesFromText(pageText);
            foundBrands.forEach(brand => brands.add(brand));
            
            console.log(`📍 Found ${foundBrands.length} brands from text analysis`);
            
        } catch (error) {
            console.log(`❌ Error in brand discovery: ${error.message}`);
        }
        
        // Try to find product links and extract brands from product pages
        try {
            const productLinks = await this.extractProductLinksFromPage();
            
            if (productLinks.length > 0) {
                console.log(`📦 Checking ${Math.min(productLinks.length, 10)} product pages for brand information...`);
                
                for (let i = 0; i < Math.min(productLinks.length, 10); i++) {
                    const productUrl = productLinks[i];
                    
                    try {
                        await this.page.goto(productUrl, { 
                            waitUntil: 'domcontentloaded',
                            timeout: 8000 
                        });
                        
                        // Look for brand information in product page
                        const brandSelectors = [
                            '.product-brand',
                            '.brand-name',
                            '.marque',
                            '[data-brand]',
                            '.brand a',
                            '.product-info .brand',
                            '.product-details .brand',
                            '.brand-text',
                            '.product-meta .brand'
                        ];
                        
                        for (const brandSelector of brandSelectors) {
                            try {
                                const brandName = await this.page.$eval(brandSelector, el => el.textContent.trim());
                                if (brandName && brandName.length > 1 && brandName.length < 50) {
                                    brands.add(brandName);
                                    console.log(`✅ Found brand: ${brandName}`);
                                }
                            } catch (e) {
                                // Continue
                            }
                        }
                        
                        // Extract from product title
                        try {
                            const title = await this.page.title();
                            const brandFromTitle = this.extractBrandFromTitle(title);
                            if (brandFromTitle) {
                                brands.add(brandFromTitle);
                                console.log(`✅ Found brand from title: ${brandFromTitle}`);
                            }
                        } catch (e) {
                            // Continue
                        }
                        
                    } catch (error) {
                        console.log(`❌ Error checking product: ${productUrl.substring(0, 50)}...`);
                    }
                    
                    await this.delay(1000);
                }
            }
            
        } catch (error) {
            console.log(`❌ Error in product brand extraction: ${error.message}`);
        }
        
        return Array.from(brands).map(brandName => ({
            name: brandName,
            url: null,
            logo: null
        }));
    }

    extractBrandNamesFromText(text) {
        // Common perfume brand names that might appear in Arabic/French sites
        const knownBrands = [
            'Chanel', 'Dior', 'Gucci', 'Versace', 'Armani', 'Tom Ford', 'Yves Saint Laurent',
            'Prada', 'Bulgari', 'Hermès', 'Givenchy', 'Lancome', 'Estée Lauder', 'Calvin Klein',
            'Hugo Boss', 'Dolce & Gabbana', 'Burberry', 'Ralph Lauren', 'Marc Jacobs', 'Kenzo',
            'Thierry Mugler', 'Jean Paul Gaultier', 'Carolina Herrera', 'Clinique', 'Cacharel'
        ];
        
        const foundBrands = [];
        
        for (const brand of knownBrands) {
            if (text.toLowerCase().includes(brand.toLowerCase())) {
                foundBrands.push(brand);
            }
        }
        
        return foundBrands;
    }

    extractBrandFromTitle(title) {
        // Try to extract brand from product title patterns
        const patterns = [
            /^([A-Z][a-z]+)\s/,  // Brand at start
            /\b([A-Z][a-z]+)\s+(?:Parfum|Fragrance|Cologne|EDT|EDP)\b/i,
            /\b(Chanel|Dior|Gucci|Versace|Armani)\b/i
        ];
        
        for (const pattern of patterns) {
            const match = title.match(pattern);
            if (match && match[1] && match[1].length > 2) {
                return match[1];
            }
        }
        
        return null;
    }

    async scrapeCategories() {
        console.log('📂 Starting categories scraping...');
        
        try {
            await this.page.goto(this.baseUrl, { waitUntil: 'networkidle2' });
            
            // Look for categories in navigation, footer, or dedicated page
            const categorySelectors = [
                'nav a',
                '.menu a',
                '.categories a',
                '.category-link',
                '.nav-link',
                '.main-menu a',
                '.footer a'
            ];
            
            const categories = new Set();
            
            for (const selector of categorySelectors) {
                try {
                    const links = await this.page.$$eval(selector, elements => {
                        return elements.map(el => ({
                            name: el.textContent.trim(),
                            url: el.href
                        })).filter(item => 
                            item.name && 
                            item.name.length > 2 && 
                            item.name.length < 50 &&
                            !item.name.toLowerCase().includes('contact') &&
                            !item.name.toLowerCase().includes('about')
                        );
                    });
                    
                    links.forEach(link => {
                        if (this.isLikelyCategory(link.name)) {
                            categories.add(JSON.stringify(link));
                        }
                    });
                    
                } catch (e) {
                    console.log(`❌ Category selector ${selector} failed`);
                }
            }
            
            // Convert back to objects and process
            const categoryArray = Array.from(categories).map(cat => JSON.parse(cat));
            
            // Remove duplicates based on name (case-insensitive)
            const uniqueCategories = this.removeDuplicateCategories(categoryArray);
            
            uniqueCategories.forEach((category, index) => {
                this.data.categories.push({
                    name: category.name,
                    slug: this.createSlug(category.name),
                    description: null,
                    image_url: null,
                    parent_id: null,
                    is_active: true,
                    sort_order: index + 1
                });
            });
            
            console.log(`✅ Categories scraping completed! Found ${this.data.categories.length} categories`);
            
        } catch (error) {
            console.error('❌ Error during categories scraping:', error);
        }
    }

    isLikelyCategory(name) {
        const categoryKeywords = [
            'parfum', 'fragrance', 'cologne', 'homme', 'femme', 'unisex',
            'eau de toilette', 'eau de parfum', 'edt', 'edp', 'luxury',
            'niche', 'designer', 'collection', 'oriental', 'floral', 'woody'
        ];
        
        const lowercaseName = name.toLowerCase();
        return categoryKeywords.some(keyword => lowercaseName.includes(keyword)) ||
               (name.length > 3 && name.length < 30 && /^[a-zA-ZÀ-ÿ\s-]+$/.test(name));
    }

    async scrapeProducts() {
        console.log('🛍️ Starting products scraping...');
        
        try {
            // First, try to find a products listing page
            await this.page.goto(this.baseUrl, { waitUntil: 'networkidle2' });
            
            // Look for product links on the homepage and category pages
            let productLinks = await this.findAllProductLinks();
            
            if (productLinks.length === 0) {
                console.log('❌ No product links found');
                return;
            }
            
            console.log(`✅ Found ${productLinks.length} total product links`);
            
            // Process products in batches to avoid timeouts
            const batchSize = 10;
            const totalBatches = Math.ceil(productLinks.length / batchSize);
            
            for (let batchIndex = 0; batchIndex < totalBatches; batchIndex++) {
                const startIndex = batchIndex * batchSize;
                const endIndex = Math.min(startIndex + batchSize, productLinks.length);
                const batch = productLinks.slice(startIndex, endIndex);
                
                console.log(`📦 Processing batch ${batchIndex + 1}/${totalBatches} (${batch.length} products)...`);
                
                for (let i = 0; i < batch.length; i++) {
                    const productUrl = batch[i];
                    const overallIndex = startIndex + i + 1;
                    
                    console.log(`🛍️ Scraping product ${overallIndex}/${productLinks.length}: ${productUrl.substring(0, 50)}...`);
                    
                    try {
                        await this.page.goto(productUrl, { 
                            waitUntil: 'domcontentloaded',
                            timeout: 10000 
                        });
                        
                        const product = await this.scrapeProductDetails();
                        
                        if (product && product.name) {
                            this.data.products.push(product);
                            console.log(`✅ Scraped: ${product.name.substring(0, 40)}...`);
                        }
                        
                    } catch (error) {
                        console.log(`❌ Error scraping product: ${error.message}`);
                    }
                    
                    // Small delay between products
                    await this.delay(800);
                }
                
                // Save progress after each batch
                if (this.data.products.length > 0) {
                    await this.saveProgressData(batchIndex + 1, totalBatches);
                }
                
                // Longer delay between batches
                await this.delay(2000);
                
                console.log(`✅ Batch ${batchIndex + 1}/${totalBatches} completed. Products scraped so far: ${this.data.products.length}`);
            }
            
            console.log(`✅ Products scraping completed! Found ${this.data.products.length} products`);
            
        } catch (error) {
            console.error('❌ Error during products scraping:', error);
        }
    }

    async findAllProductLinks() {
        console.log('🔍 Finding all product links...');
        
        const productLinks = new Set();
        
        // 1. Check homepage for product links
        await this.page.goto(this.baseUrl, { waitUntil: 'networkidle2' });
        
        const homepageProducts = await this.extractProductLinksFromPage();
        homepageProducts.forEach(link => productLinks.add(link));
        console.log(`📍 Found ${homepageProducts.length} products on homepage`);
        
        // 2. Check category pages for more products
        for (const category of this.data.categories) {
            if (category.name && category.name.includes('PARFUM')) {
                try {
                    // Try to navigate to category page
                    const categoryUrl = `${this.baseUrl}/${category.slug}`;
                    await this.page.goto(categoryUrl, { 
                        waitUntil: 'domcontentloaded',
                        timeout: 8000 
                    });
                    
                    const categoryProducts = await this.extractProductLinksFromPage();
                    const newProducts = categoryProducts.filter(link => !productLinks.has(link));
                    
                    if (newProducts.length > 0) {
                        newProducts.forEach(link => productLinks.add(link));
                        console.log(`📍 Found ${newProducts.length} new products in category: ${category.name}`);
                    }
                    
                    await this.delay(1000);
                    
                } catch (error) {
                    console.log(`❌ Error checking category ${category.name}: ${error.message}`);
                }
                
                // Limit category checking to avoid too long execution
                if (productLinks.size > 50) break;
            }
        }
        
        return Array.from(productLinks);
    }

    async extractProductLinksFromPage() {
        const productSelectors = [
            'a[href*="/product"]',
            'a[href*="/produit"]',
            '.product-item a',
            '.produit-item a',
            '.product-card a',
            '.product a',
            '[class*="product"] a',
            '.item a[href*="detail"]'
        ];
        
        const allLinks = new Set();
        
        for (const selector of productSelectors) {
            try {
                const links = await this.page.$$eval(selector, elements => 
                    elements.map(el => el.href).filter(href => 
                        href && href.includes('pafen-dz.com')
                    )
                );
                
                links.forEach(link => allLinks.add(link));
                
            } catch (e) {
                // Continue with next selector
            }
        }
        
        // Also try generic approach
        try {
            const genericLinks = await this.page.$$eval('a', links => 
                links.map(link => link.href).filter(url => 
                    url && (
                        url.includes('/product') || 
                        url.includes('/produit') ||
                        url.includes('item') ||
                        url.includes('detail')
                    ) && url.includes('pafen-dz.com')
                )
            );
            
            genericLinks.forEach(link => allLinks.add(link));
            
        } catch (e) {
            // Continue
        }
        
        return Array.from(allLinks);
    }

    async saveProgressData(currentBatch, totalBatches) {
        try {
            const outputDir = './scraped-data';
            await fs.ensureDir(outputDir);
            
            // Save current progress
            const progressFile = path.join(outputDir, 'products-progress.json');
            const progressData = {
                current_batch: currentBatch,
                total_batches: totalBatches,
                products_scraped: this.data.products.length,
                last_updated: new Date().toISOString(),
                products: this.data.products
            };
            
            await fs.writeJSON(progressFile, progressData, { spaces: 2 });
            console.log(`💾 Progress saved: ${this.data.products.length} products (batch ${currentBatch}/${totalBatches})`);
            
        } catch (error) {
            console.log(`❌ Error saving progress: ${error.message}`);
        }
    }

    async scrapeProductDetails() {
        try {
            // Scrape product name
            const nameSelectors = [
                'h1', '.product-title', '.product-name', '.titre', '.title'
            ];
            let name = null;
            
            for (const selector of nameSelectors) {
                try {
                    name = await this.page.$eval(selector, el => el.textContent.trim());
                    if (name && name.length > 2) break;
                } catch (e) {}
            }
            
            // Scrape product price
            const priceSelectors = [
                '.price', '.prix', '.product-price', '.cost', '[class*="price"]'
            ];
            let price = null;
            
            for (const selector of priceSelectors) {
                try {
                    const priceText = await this.page.$eval(selector, el => el.textContent.trim());
                    const priceMatch = priceText.match(/[\d,]+(?:\.\d+)?/);
                    if (priceMatch) {
                        price = parseFloat(priceMatch[0].replace(',', ''));
                        break;
                    }
                } catch (e) {}
            }
            
            // Scrape product description
            const descSelectors = [
                '.description', '.product-description', '.content', '.details', 'p'
            ];
            let description = null;
            
            for (const selector of descSelectors) {
                try {
                    description = await this.page.$eval(selector, el => el.textContent.trim());
                    if (description && description.length > 20) break;
                } catch (e) {}
            }
            
            // Scrape product image
            const imageSelectors = [
                '.product-image img', '.product-photo img', '.main-image img', 'img'
            ];
            let imageUrl = null;
            
            for (const selector of imageSelectors) {
                try {
                    imageUrl = await this.page.$eval(selector, img => img.src);
                    if (imageUrl && !imageUrl.includes('placeholder') && !imageUrl.includes('default')) {
                        break;
                    }
                } catch (e) {}
            }
            
            // Extract brand from name or page
            let brand = null;
            if (name) {
                brand = this.extractBrandFromTitle(name);
            }
            
            return {
                name: name,
                slug: name ? this.createSlug(name) : null,
                description: description,
                price: price,
                image_url: imageUrl,
                brand: brand,
                sku: this.generateSKU(name),
                is_active: true,
                stock_quantity: Math.floor(Math.random() * 50) + 10, // Random stock
                url: this.page.url()
            };
            
        } catch (error) {
            console.log(`❌ Error scraping product details: ${error.message}`);
            return null;
        }
    }

    generateSKU(name) {
        if (!name) return null;
        return name.toUpperCase()
                  .replace(/[^A-Z0-9]/g, '')
                  .substring(0, 8) + Math.floor(Math.random() * 1000);
    }

    createSlug(text) {
        if (!text) return '';
        return text
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '') // Remove accents
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
    }

    removeDuplicateCategories(categories) {
        const seen = new Set();
        return categories.filter(category => {
            const key = category.name.toLowerCase().trim();
            if (seen.has(key)) {
                return false;
            }
            seen.add(key);
            return true;
        });
    }

    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async saveData() {
        console.log('💾 Saving scraped data...');
        
        const outputDir = './scraped-data';
        await fs.ensureDir(outputDir);
        
        // Save brands
        if (this.data.brands.length > 0) {
            const brandsFile = path.join(outputDir, 'brands.json');
            await fs.writeJSON(brandsFile, this.data.brands, { spaces: 2 });
            console.log(`✅ Saved ${this.data.brands.length} brands to ${brandsFile}`);
        }
        
        // Save categories
        if (this.data.categories.length > 0) {
            const categoriesFile = path.join(outputDir, 'categories.json');
            await fs.writeJSON(categoriesFile, this.data.categories, { spaces: 2 });
            console.log(`✅ Saved ${this.data.categories.length} categories to ${categoriesFile}`);
        }
        
        // Save products
        if (this.data.products.length > 0) {
            const productsFile = path.join(outputDir, 'products.json');
            await fs.writeJSON(productsFile, this.data.products, { spaces: 2 });
            console.log(`✅ Saved ${this.data.products.length} products to ${productsFile}`);
        }
        
        // Save combined data
        const allDataFile = path.join(outputDir, 'pafen-data.json');
        await fs.writeJSON(allDataFile, this.data, { spaces: 2 });
        console.log(`✅ Saved all data to ${allDataFile}`);
        
        // Save summary
        const summary = {
            scraping_date: new Date().toISOString(),
            source_website: this.baseUrl,
            total_brands: this.data.brands.length,
            total_categories: this.data.categories.length,
            total_products: this.data.products.length,
            brands_with_logos: this.data.brands.filter(b => b.logo_url).length,
            products_with_images: this.data.products.filter(p => p.image_url).length
        };
        
        const summaryFile = path.join(outputDir, 'scraping-summary.json');
        await fs.writeJSON(summaryFile, summary, { spaces: 2 });
        console.log(`✅ Saved scraping summary to ${summaryFile}`);
    }

    async cleanup() {
        if (this.browser) {
            await this.browser.close();
            console.log('🧹 Browser closed');
        }
    }

    async run() {
        const startTime = Date.now();
        
        try {
            await this.init();
            
            // Check command line arguments
            const args = process.argv.slice(2);
            
            if (args.includes('--brands')) {
                await this.scrapeBrands();
            } else if (args.includes('--categories')) {
                await this.scrapeCategories();
            } else if (args.includes('--products')) {
                await this.scrapeProducts();
            } else {
                // Scrape everything
                console.log('🎯 Starting complete scraping process...');
                await this.scrapeBrands();
                await this.scrapeCategories();
                await this.scrapeProducts();
            }
            
            // Save all data
            await this.saveData();
            
            const endTime = Date.now();
            const duration = Math.round((endTime - startTime) / 1000);
            
            console.log(`\n🎉 Scraping completed successfully!`);
            console.log(`⏱️  Total time: ${duration}s`);
            console.log(`📋 Brands: ${this.data.brands.length}`);
            console.log(`📂 Categories: ${this.data.categories.length}`);
            console.log(`🛍️  Products: ${this.data.products.length}`);
            
        } catch (error) {
            console.error('❌ Scraping failed:', error);
        } finally {
            await this.cleanup();
        }
    }
}

// Run the scraper
async function main() {
    console.log('🎯 Starting Pafen-DZ scraper...');
    console.log('🌐 Target website: https://pafen-dz.com');
    console.log('📅 Started at:', new Date().toLocaleString());
    console.log('─'.repeat(50));
    
    const scraper = new PafenScraper();
    await scraper.run();
    
    console.log('─'.repeat(50));
    console.log('🏁 Scraper finished!');
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