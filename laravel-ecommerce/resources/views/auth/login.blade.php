@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center" style="background-color: #f8f9fa;">
    <div class="max-w-md w-full space-y-8 px-6">
        <div class="bg-white p-8 shadow-lg" style="border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary flex items-center justify-center mx-auto mb-4 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">@t('Login')</h2>
                <p class="mt-2 text-gray-600">@t('Welcome back! Please sign in to your account')</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200" style="border-radius: 12px;">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-red-800">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

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

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-primary focus:ring-primary border-gray-300" style="border-radius: 4px;">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">@t('Remember me')</label>
                    </div>
                    <a href="#" class="text-sm text-primary hover:text-primary-700 font-medium">@t('Forgot password?')</a>
                </div>

                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-700 text-white font-medium py-3 px-4 transition-all duration-200 hover:scale-105 hover:shadow-lg"
                        style="border-radius: 12px;">
                    @t('Sign In')
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    @t('Don\'t have an account?')
                    <a href="{{ route('register') }}" class="text-primary hover:text-primary-700 font-medium">@t('Sign up here')</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection