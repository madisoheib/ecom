@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center" style="background-color: #f8f9fa;">
    <div class="max-w-md w-full space-y-8 px-6">
        <div class="bg-white p-8 shadow-lg" style="border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary flex items-center justify-center mx-auto mb-4 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">@t('Register')</h2>
                <p class="mt-2 text-gray-600">@t('Create your account to get started')</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200" style="border-radius: 12px;">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="ml-3">
                            <ul class="text-sm text-red-800">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">@t('Full Name')</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                           style="border-radius: 12px;"
                           placeholder="@t('Enter your full name')">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">@t('Email Address')</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                           style="border-radius: 12px;"
                           placeholder="@t('Enter your email')">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">@t('Password')</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                           style="border-radius: 12px;"
                           placeholder="@t('Enter your password')">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">@t('Confirm Password')</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                           style="border-radius: 12px;"
                           placeholder="@t('Confirm your password')">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="terms" name="terms" required class="h-4 w-4 text-primary focus:ring-primary border-gray-300" style="border-radius: 4px;">
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        @t('I agree to the') <a href="#" class="text-primary hover:text-primary-700 font-medium">@t('Terms of Service')</a> @t('and') <a href="#" class="text-primary hover:text-primary-700 font-medium">@t('Privacy Policy')</a>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-700 text-white font-medium py-3 px-4 transition-all duration-200 hover:scale-105 hover:shadow-lg"
                        style="border-radius: 12px;">
                    @t('Create Account')
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    @t('Already have an account?')
                    <a href="{{ route('login') }}" class="text-primary hover:text-primary-700 font-medium">@t('Sign in here')</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection