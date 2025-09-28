/**
 * Layout JavaScript Functions
 * Contains cart, search, and language switching functionality
 */

// Initialize cart data from server
window.cartData = {
    items: [],
    total: 0,
    count: 0
};

// Initialize cart count display
document.addEventListener('DOMContentLoaded', function() {
    const cartElements = document.querySelectorAll('[x-text="window.cartData.count"], .cart-count');
    cartElements.forEach(el => el.textContent = window.cartData.count);
    refreshCartSidebar();
});

/**
 * Language switcher function
 * @param {string} locale - The locale code to switch to
 */
function switchLanguage(locale) {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('lang', locale);
    window.location.href = currentUrl.toString();
}

/**
 * Search functionality
 * Handles both desktop and mobile search inputs
 */
function search() {
    let query = '';
    const desktopInput = document.querySelector('#search-input');
    const mobileInput = document.querySelector('#mobile-search-input');

    if (desktopInput && desktopInput.value.trim()) {
        query = desktopInput.value;
    } else if (mobileInput && mobileInput.value.trim()) {
        query = mobileInput.value;
    }

    if (query.trim()) {
        window.location.href = '/produits?search=' + encodeURIComponent(query);
    }
}

/**
 * Add to cart functionality
 * @param {number} productId - Product ID to add
 * @param {string} productName - Product name (optional)
 * @param {number} quantity - Quantity to add (default: 1)
 */
function addToCart(productId, productName = '', quantity = 1) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.cartData.count = data.cart_count || data.count;
            const cartElements = document.querySelectorAll('[x-text="window.cartData.count"], .cart-count');
            cartElements.forEach(el => el.textContent = window.cartData.count);
            alert('Product added to cart!');
        } else {
            alert(data.message || 'Failed to add product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

/**
 * Refresh cart sidebar
 * Simplified cart sidebar refresh for components
 */
function refreshCartSidebar() {
    // Implementation for cart sidebar refresh
    // This function can be expanded based on specific cart sidebar requirements
}