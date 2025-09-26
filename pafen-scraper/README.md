# Pafen-DZ Scraper

A Node.js web scraper to extract brands, categories, and products from https://pafen-dz.com for use in Laravel e-commerce seeders.

## Features

- 🏷️ **Brand Scraping**: Extracts brand names, logos, and descriptions
- 📂 **Category Scraping**: Discovers product categories and navigation structure
- 🛍️ **Product Scraping**: Collects product names, prices, descriptions, and images
- 📊 **JSON Export**: Outputs structured data for Laravel seeders
- 🔄 **Smart Detection**: Uses multiple selectors and fallback methods
- 📸 **Debug Screenshots**: Saves screenshots for troubleshooting

## Installation

1. Navigate to the scraper directory:
```bash
cd pafen-scraper
```

2. Install dependencies:
```bash
npm install
```

## Usage

### Scrape Everything
```bash
npm run scrape
# or
node scraper.js
```

### Scrape Specific Data Types
```bash
# Only brands
npm run scrape:brands
node scraper.js --brands

# Only categories  
npm run scrape:categories
node scraper.js --categories

# Only products
npm run scrape:products
node scraper.js --products
```

### Development Mode (with auto-restart)
```bash
npm run dev
```

## Output

The scraper creates a `scraped-data` directory with the following files:

- `brands.json` - Array of brand objects with name, slug, logo URL, etc.
- `categories.json` - Array of category objects 
- `products.json` - Array of product objects with name, price, description, image
- `pafen-data.json` - Combined data from all scraping
- `scraping-summary.json` - Summary statistics and metadata

### Example Brand Object
```json
{
  "name": "Chanel",
  "slug": "chanel", 
  "description": "Luxury French fashion house...",
  "logo_url": "https://pafen-dz.com/images/brands/chanel-logo.jpg",
  "website_url": "https://pafen-dz.com/marques/chanel",
  "is_active": true,
  "sort_order": 1
}
```

### Example Product Object
```json
{
  "name": "Chanel N°5 Eau de Parfum",
  "slug": "chanel-n5-eau-de-parfum",
  "description": "The quintessential fragrance...", 
  "price": 125.50,
  "image_url": "https://pafen-dz.com/images/products/chanel-n5.jpg",
  "brand": "Chanel",
  "sku": "CHANN5EP123",
  "is_active": true,
  "stock_quantity": 25,
  "url": "https://pafen-dz.com/produits/chanel-n5-edp"
}
```

## Configuration

### Browser Settings
The scraper runs in **visible mode** by default for debugging. To run headless:

```javascript
// In scraper.js, change:
headless: false  // to
headless: true
```

### Scraping Limits
- **Brands**: No limit (scrapes all found)
- **Categories**: No limit (scrapes all found) 
- **Products**: Limited to 15 products (configurable in code)

### Delays
- Between brand processing: 1000ms
- Between product scraping: 1500ms
- Between page navigations: 500ms

## Troubleshooting

### Debug Screenshots
The scraper automatically saves debug screenshots:
- `debug-homepage.png` - Homepage after loading
- `debug-brands-page.png` - Brands page if found

### Common Issues

1. **No data found**: Check if selectors match the website structure
2. **Timeout errors**: Increase timeout values in the code
3. **Missing images**: Some images might not load or be placeholders
4. **Browser launch fails**: Install Chromium dependencies

### Verbose Logging
The scraper provides detailed console output showing:
- ✅ Successful operations
- ❌ Failed attempts with reasons  
- 🔍 Discovery processes
- 📦 Processing progress

## Using with Laravel

After scraping, copy the JSON files to your Laravel project:

```bash
# Copy to Laravel seeders directory
cp scraped-data/*.json ../laravel-ecommerce/database/seeders/data/
```

Then create Laravel seeders that read from these JSON files:

```php
// BrandSeeder.php
$brands = json_decode(file_get_contents(__DIR__ . '/data/brands.json'), true);
foreach ($brands as $brandData) {
    Brand::create($brandData);
}
```

## Dependencies

- **puppeteer**: Headless Chrome automation
- **fs-extra**: Enhanced file system operations  
- **cheerio**: Server-side HTML parsing (backup method)
- **axios**: HTTP client (for API calls if needed)

## Notes

- Respects robots.txt and implements delays to avoid overwhelming the server
- Uses realistic user agent and browser settings
- Handles dynamic content loading with networkidle2 waiting
- Implements multiple fallback strategies for data extraction
- Generates Laravel-ready data structures with slugs and proper formatting

## License

MIT License - Free to use and modify for your Laravel e-commerce project.