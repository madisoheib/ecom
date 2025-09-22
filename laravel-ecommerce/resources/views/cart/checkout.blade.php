@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Checkout Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-6">Billing Information</h2>

                    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" id="first_name" name="first_name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" id="last_name" name="last_name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <input type="text" id="address" name="address" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" id="city" name="city" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                                <input type="text" id="state" name="state" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">ZIP Code</label>
                                <input type="text" id="zip_code" name="zip_code" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <select id="country" name="country" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select Country</option>
                                <option value="US">United States</option>
                                <option value="CA">Canada</option>
                                <option value="GB">United Kingdom</option>
                                <option value="AU">Australia</option>
                                <!-- Add more countries as needed -->
                            </select>
                        </div>

                        <div class="border-t pt-6 mt-6">
                            <h3 class="text-lg font-semibold mb-4">Payment Method</h3>

                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="credit_card" checked
                                           class="text-primary focus:ring-primary">
                                    <span class="ml-2">Credit Card</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="paypal"
                                           class="text-primary focus:ring-primary">
                                    <span class="ml-2">PayPal</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="bank_transfer"
                                           class="text-primary focus:ring-primary">
                                    <span class="ml-2">Bank Transfer</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit"
                                    class="w-full bg-primary text-white py-3 px-4 rounded-md hover:bg-opacity-90 font-semibold transition duration-200">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-6">Order Summary</h2>

                    <div class="space-y-4">
                        @foreach($cart as $id => $item)
                            <div class="flex justify-between items-center py-2 border-b">
                                <div class="flex-1">
                                    <h4 class="font-medium">{{ $item['name'] }}</h4>
                                    <p class="text-sm text-gray-600">Qty: {{ $item['quantity'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">{{ site_currency() }} {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4 mt-4">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Subtotal:</span>
                            <span>{{ site_currency() }} {{ number_format($cartTotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Shipping:</span>
                            <span>{{ site_currency() }} 0.00</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Tax:</span>
                            <span>{{ site_currency() }} {{ number_format($cartTotal * 0.1, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-t font-bold text-lg">
                            <span>Total:</span>
                            <span>{{ site_currency() }} {{ number_format($cartTotal * 1.1, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <h3 class="font-semibold mb-3">We Accept:</h3>
                        <div class="flex space-x-2">
                            <div class="w-12 h-8 bg-blue-600 rounded flex items-center justify-center text-white text-xs font-bold">VISA</div>
                            <div class="w-12 h-8 bg-red-600 rounded flex items-center justify-center text-white text-xs font-bold">MC</div>
                            <div class="w-12 h-8 bg-blue-800 rounded flex items-center justify-center text-white text-xs font-bold">AMEX</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection