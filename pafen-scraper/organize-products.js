const fs = require('fs-extra');
const path = require('path');

async function organizeProducts() {
    try {
        console.log('📂 Organizing existing products by category...');
        
        // Read existing products
        const productsFile = path.join(__dirname, 'scraped-data', 'products-full.json');
        const products = await fs.readJSON(productsFile);
        
        console.log(`📦 Found ${products.length} products to organize`);
        
        // Group products by category (extract from URL pattern)
        const productsByCategory = {};
        
        for (const product of products) {
            // Try to determine category from URL or create a generic category
            let category = 'general';
            
            if (product.url) {
                // Look for category patterns in URL
                if (product.url.includes('homme') || product.name.toLowerCase().includes('homme')) {
                    category = 'parfums-homme';
                } else if (product.url.includes('femme') || product.name.toLowerCase().includes('femme')) {
                    category = 'parfums-femme';
                } else if (product.name.toLowerCase().includes('niche')) {
                    category = 'parfums-de-niche';
                } else if (product.name.toLowerCase().includes('coffret')) {
                    category = 'coffrets';
                } else {
                    category = 'parfums-mixte';
                }
            }
            
            if (!productsByCategory[category]) {
                productsByCategory[category] = [];
            }
            
            // Add category field to product
            const enrichedProduct = {
                ...product,
                category: category.replace('-', ' ').toUpperCase(),
                title: product.name, // Ensure we have title field
                content: product.description, // Add content field
                marque: product.brand // Add marque (French for brand)
            };
            
            productsByCategory[category].push(enrichedProduct);
        }
        
        // Ensure products directory exists
        const productsDir = path.join(__dirname, 'products');
        await fs.ensureDir(productsDir);
        
        // Save organized products by category
        for (const [category, categoryProducts] of Object.entries(productsByCategory)) {
            const fileName = `${category}.json`;
            await fs.writeJSON(path.join(productsDir, fileName), categoryProducts, { spaces: 2 });
            console.log(`✅ Saved ${categoryProducts.length} products to products/${fileName}`);
        }
        
        // Create summary
        const summary = {
            total_products: products.length,
            categories: Object.keys(productsByCategory),
            products_by_category: Object.fromEntries(
                Object.entries(productsByCategory).map(([cat, prods]) => [cat, prods.length])
            ),
            organization_date: new Date().toISOString()
        };
        
        await fs.writeJSON(path.join(productsDir, '_summary.json'), summary, { spaces: 2 });
        
        console.log('\n📊 Organization Summary:');
        console.log(`Total products: ${summary.total_products}`);
        console.log('Products by category:');
        for (const [category, count] of Object.entries(summary.products_by_category)) {
            console.log(`  ${category}: ${count} products`);
        }
        
        console.log('\n🎉 Product organization completed successfully!');
        
    } catch (error) {
        console.error('❌ Error organizing products:', error.message);
        throw error;
    }
}

// Run if this file is executed directly
if (require.main === module) {
    organizeProducts();
}

module.exports = organizeProducts;