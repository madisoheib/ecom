<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Slider;
use App\Services\SeoService;

class HomeController extends Controller
{
    public function index(SeoService $seoService)
    {
        // Get active sliders
        $sliders = Slider::active()
            ->ordered()
            ->get();

        // Get featured products
        $featuredProducts = Product::active()
            ->featured()
            ->with(['brand', 'categories', 'images'])
            ->limit(8)
            ->get();

        // Get recent products
        $recentProducts = Product::active()
            ->with(['brand', 'categories', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Get most viewed products
        $popularProducts = Product::active()
            ->with(['brand', 'categories', 'images'])
            ->orderBy('views_count', 'desc')
            ->limit(8)
            ->get();

        // Get main categories
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        // Get featured brands
        $brands = Brand::where('is_active', true)
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        // SEO Meta
        $meta = $seoService->generateMetaTags();

        return view('home-simple', compact(
            'sliders',
            'featuredProducts',
            'recentProducts',
            'popularProducts',
            'categories',
            'brands',
            'meta'
        ));
    }
}
