<!-- Cart Sidebar -->
<div x-data="{
        open: false,
        cartItems: [],
        cartTotal: {{ session()->get('cart_total', 0) }},
        cartCount: {{ session()->get('cart_count', 0) }},
        updateCartDisplay() {
            // Update from global cart data
            this.cartItems = window.cartData?.items || [];
            this.cartTotal = window.cartData?.total || {{ session()->get('cart_total', 0) }};
            this.cartCount = window.cartData?.count || {{ session()->get('cart_count', 0) }};
        },
        removeFromCartSidebar(productId) {
            window.removeFromCartSidebar(productId);
        }
     }"
     @cart-toggle.window="open = !open; if(open) { updateCartDisplay(); }"
     @cart-updated.window="updateCartDisplay()"
     x-cloak>
    <!-- Backdrop -->
    <div x-show="open"
         @click="open = false"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar -->
    <div x-show="open"
         class="fixed {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} top-0 h-full w-96 bg-white shadow-xl z-50 transform transition-transform"
         style="box-shadow: -10px 0 25px rgba(0, 0, 0, 0.15);"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="{{ app()->getLocale() === 'ar' ? '-translate-x-full' : 'translate-x-full' }}"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="{{ app()->getLocale() === 'ar' ? '-translate-x-full' : 'translate-x-full' }}">

        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-subheading text-gray-900">@t('Panier')</h2>
                        <p class="text-xs text-gray-500" x-text="cartCount + ' article' + (cartCount > 1 ? 's' : '')">0 articles</p>
                    </div>
                </div>
                <button @click="open = false" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto" x-show="cartCount > 0">
                <div class="p-6 space-y-4" id="sidebar-cart-items">
                    <!-- Dynamic cart items using Alpine.js -->
                    <template x-for="item in cartItems" :key="item.id">
                        <div class="flex items-center space-x-4 p-4 bg-white border border-gray-100 rounded-xl hover:shadow-md transition-all duration-200 cart-item" :data-id="item.id">
                            <div class="flex-shrink-0">
                                <img :src="item.image" 
                                     :alt="item.name"
                                     class="w-16 h-16 object-cover rounded-lg shadow-sm">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-subheading text-gray-900 leading-tight mb-1" x-text="item.name"></h4>
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-600">
                                        <span x-text="item.quantity"></span> × $<span x-text="parseFloat(item.price).toFixed(2)"></span>
                                    </p>
                                    <p class="text-sm font-subheading text-primary">
                                        $<span x-text="item.subtotal.toFixed(2)"></span>
                                    </p>
                                </div>
                            </div>
                            <button @click="removeFromCartSidebar(item.id)" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Empty Cart State -->
            <div x-show="cartCount === 0" class="flex-1 flex items-center justify-center p-6">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-subheading text-gray-900 mb-2">@t('Votre panier est vide')</h3>
                    <p class="text-sm text-gray-500 mb-4">@t('Ajoutez des produits pour commencer')</p>
                    <button @click="open = false" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        @t('Continuer les achats')
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div x-show="cartCount > 0" class="border-t border-gray-100 bg-gray-50 p-6 space-y-4">
                <!-- Total -->
                <div class="flex justify-between items-center">
                    <span class="text-lg font-subheading text-gray-900">@t('Total')</span>
                    <span class="text-xl font-heading text-primary" id="sidebar-cart-total">$<span x-text="cartTotal.toFixed(2)"></span></span>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ route('cart.index') }}"
                       @click="open = false"
                       class="w-full bg-white border-2 border-gray-200 text-gray-900 py-3 px-4 rounded-xl text-center block hover:border-gray-300 hover:shadow-sm transition-all font-subheading">
                        @t('Voir le panier')
                    </a>
                    <a href="{{ url('/commande') }}"
                       @click="open = false"
                       class="w-full bg-gradient-to-r from-primary to-primary-700 text-white py-3 px-4 rounded-xl text-center block hover:shadow-lg hover:scale-105 transition-all font-subheading">
                        @t('Commander')
                        <svg class="w-4 h-4 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>