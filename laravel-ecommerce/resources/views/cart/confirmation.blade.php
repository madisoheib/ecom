@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto text-center">
            <div class="bg-green-50 border border-green-200 rounded-lg p-8 mb-8">
                <div class="flex justify-center mb-4">
                    <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-green-800 mb-2">Order Confirmed!</h1>
                <p class="text-green-700 mb-4">Thank you for your purchase. Your order has been successfully placed.</p>

                <div class="bg-white rounded-md p-4 mb-6">
                    <p class="text-sm text-gray-600">Order Number:</p>
                    <p class="font-mono text-lg font-semibold text-gray-900">#{{ $order }}</p>
                </div>

                <p class="text-sm text-gray-600 mb-6">
                    You will receive an email confirmation with your order details and tracking information shortly.
                </p>

                <div class="space-y-3">
                    <a href="{{ route('home') }}"
                       class="inline-block w-full bg-primary text-white py-3 px-6 rounded-md hover:bg-opacity-90 font-semibold transition duration-200">
                        Continue Shopping
                    </a>

                    @auth
                    <a href="{{ route('user.orders') }}"
                       class="inline-block w-full bg-gray-100 text-gray-800 py-3 px-6 rounded-md hover:bg-gray-200 font-semibold transition duration-200">
                        View Order History
                    </a>
                    @endauth
                </div>
            </div>

            <div class="text-center text-gray-600">
                <h3 class="font-semibold mb-2">Need Help?</h3>
                <p class="text-sm">
                    If you have any questions about your order, please contact our customer service team.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection