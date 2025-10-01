/**
 * Main Application JavaScript
 * Contains cart, search, language switching, and other core functionality
 */

// Initialize cart data from server
window.cartData = {
    items: [],
    total: 0,
    count: 0
};

// DOM Content Loaded Event
document.addEventListener('DOMContentLoaded', function() {
    initializeCart();
    initializeScrollToTop();
    initializeTooltips();
});

/**
 * Initialize cart display
 */
function initializeCart() {
    const cartElements = document.querySelectorAll('[x-text="window.cartData.count"], .cart-count');
    cartElements.forEach(el => el.textContent = window.cartData.count);
    refreshCartSidebar();
}

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
 * Add to cart functionality with enhanced error handling and animations
 * @param {number} productId - Product ID to add
 * @param {string} productName - Product name (optional)
 * @param {number} quantity - Quantity to add (default: 1)
 */
function addToCart(productId, productName = '', quantity = 1) {
    // Show loading state
    const addButton = event?.target;
    if (addButton) {
        addButton.disabled = true;
        addButton.classList.add('loading-spinner');
    }

    const cartUrl = window.cartRoutes?.add || '/panier/ajouter';
    fetch(cartUrl, {
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
            // Update cart count
            window.cartData.count = data.cart_count || data.count;
            const cartElements = document.querySelectorAll('[x-text="window.cartData.count"], .cart-count');
            cartElements.forEach(el => {
                el.textContent = window.cartData.count;
                el.classList.add('cart-bounce');
                setTimeout(() => el.classList.remove('cart-bounce'), 300);
            });

            // Show success toast
            showToast('Product added to cart!', 'success');
            refreshCartSidebar();
        } else {
            showToast(data.message || 'Failed to add product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        // Reset button state
        if (addButton) {
            addButton.disabled = false;
            addButton.classList.remove('loading-spinner');
        }
    });
}

/**
 * Refresh cart sidebar
 */
function refreshCartSidebar() {
    // Fetch updated cart data from server
    fetch(window.cartRoutes?.data || '/panier/data')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update global cart data
                window.cartData = {
                    items: data.cart,
                    total: data.cart_total,
                    count: data.cart_count
                };

                // Update cart count in header and other elements
                const cartElements = document.querySelectorAll('[x-text="window.cartData.count"], .cart-count');
                cartElements.forEach(el => {
                    el.textContent = window.cartData.count;
                });

                // Trigger Alpine.js cart update event
                window.dispatchEvent(new CustomEvent('cart-updated'));
                
                // Force Alpine.js to update by triggering reactivity
                // This ensures the cart sidebar updates with new data
            }
        })
        .catch(error => {
            console.error('Error refreshing cart:', error);
        });
}

/**
 * Update cart sidebar content dynamically
 * @param {Array} cartItems - Cart items array
 * @param {Number} cartTotal - Cart total amount
 */
function updateCartSidebarContent(cartItems, cartTotal) {
    const cartItemsContainer = document.getElementById('sidebar-cart-items');
    const cartTotalElement = document.getElementById('sidebar-cart-total');
    
    if (!cartItemsContainer) return;

    // Clear current items
    cartItemsContainer.innerHTML = '';

    // Cosmetic images for products
    const perfumeImages = [
        'https://images.unsplash.com/photo-1541643600914-78b084683601?w=80&h=80&fit=crop',
        'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=80&h=80&fit=crop',
        'https://images.unsplash.com/photo-1594736797933-d0301ba2fe65?w=80&h=80&fit=crop',
        'https://images.unsplash.com/photo-1615634260167-c8cdede054de?w=80&h=80&fit=crop',
        'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=80&h=80&fit=crop',
        'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=80&h=80&fit=crop',
        'https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?w=80&h=80&fit=crop',
        'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=80&h=80&fit=crop'
    ];

    // Add each cart item
    cartItems.forEach(item => {
        const imageUrl = perfumeImages[(item.id - 1) % perfumeImages.length];
        const itemElement = document.createElement('div');
        itemElement.className = 'flex items-center space-x-4 p-4 bg-white border border-gray-100 rounded-xl hover:shadow-md transition-all duration-200 cart-item';
        itemElement.setAttribute('data-id', item.id);
        
        itemElement.innerHTML = `
            <div class="flex-shrink-0">
                <img src="${imageUrl}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg shadow-sm">
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-subheading text-gray-900 leading-tight mb-1">${item.name}</h4>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">${item.quantity} × $${item.price.toFixed(2)}</p>
                    <p class="text-sm font-subheading text-primary">$${item.subtotal.toFixed(2)}</p>
                </div>
            </div>
            <button onclick="removeFromCartSidebar(${item.id})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        `;
        
        cartItemsContainer.appendChild(itemElement);
    });

    // Update cart total
    if (cartTotalElement) {
        cartTotalElement.textContent = `$${cartTotal.toFixed(2)}`;
    }
}

/**
 * Remove item from cart sidebar
 * @param {number} productId - Product ID to remove
 */
function removeFromCartSidebar(productId) {
    const removeUrl = window.cartRoutes?.remove ? `${window.cartRoutes.remove}/${productId}` : `/panier/supprimer/${productId}`;
    
    fetch(removeUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Product removed from cart', 'success');
            refreshCartSidebar();
        } else {
            showToast(data.message || 'Failed to remove product', 'error');
        }
    })
    .catch(error => {
        console.error('Error removing item:', error);
        showToast('An error occurred. Please try again.', 'error');
    });
}

/**
 * Show toast notification
 * @param {string} message - The message to display
 * @param {string} type - The type of toast (success, error, warning, info)
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast fixed top-4 right-4 max-w-sm p-4 mb-4 text-sm text-white rounded-lg z-50 ${getToastTypeClass(type)}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                ${getToastIcon(type)}
            </div>
            <div class="ml-3 text-sm font-normal">${message}</div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-white rounded-lg focus:ring-2 p-1.5 inline-flex h-8 w-8" onclick="this.parentElement.parentElement.remove()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto-remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('removing');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Get toast type class
 */
function getToastTypeClass(type) {
    const classes = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    return classes[type] || classes.info;
}

/**
 * Get toast icon
 */
function getToastIcon(type) {
    const icons = {
        success: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
        error: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>',
        warning: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
        info: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
    };
    return icons[type] || icons.info;
}

/**
 * Initialize scroll to top functionality
 */
function initializeScrollToTop() {
    const scrollButton = document.getElementById('scroll-to-top');
    if (!scrollButton) return;

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollButton.classList.add('visible');
        } else {
            scrollButton.classList.remove('visible');
        }
    });

    scrollButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    // Simple tooltip implementation
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

/**
 * Show tooltip
 */
function showTooltip(event) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip-popup fixed bg-gray-800 text-white text-sm rounded px-2 py-1 z-50 pointer-events-none';
    tooltip.textContent = event.target.getAttribute('data-tooltip');

    document.body.appendChild(tooltip);

    const rect = event.target.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';

    event.target._tooltip = tooltip;
}

/**
 * Hide tooltip
 */
function hideTooltip(event) {
    if (event.target._tooltip) {
        event.target._tooltip.remove();
        delete event.target._tooltip;
    }
}

/**
 * Handle form submissions with loading states
 */
function handleFormSubmit(form, callback) {
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;

    submitButton.disabled = true;
    submitButton.textContent = 'Loading...';
    submitButton.classList.add('loading-spinner');

    if (callback) {
        callback().finally(() => {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            submitButton.classList.remove('loading-spinner');
        });
    }
}

/**
 * Utility function to debounce function calls
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}