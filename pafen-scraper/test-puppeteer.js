const puppeteer = require('puppeteer');

async function testPuppeteer() {
    console.log('🧪 Testing Puppeteer installation...');
    
    try {
        const browser = await puppeteer.launch({
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });
        
        console.log('✅ Browser launched successfully');
        
        const page = await browser.newPage();
        console.log('✅ Page created successfully');
        
        await page.goto('https://example.com', { waitUntil: 'networkidle2' });
        console.log('✅ Navigation successful');
        
        const title = await page.title();
        console.log('✅ Page title:', title);
        
        await browser.close();
        console.log('✅ Browser closed successfully');
        
        console.log('🎉 Puppeteer is working correctly!');
        
    } catch (error) {
        console.error('❌ Puppeteer test failed:', error.message);
        console.error('Stack:', error.stack);
    }
}

testPuppeteer();