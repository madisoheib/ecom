<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Services\SeoService;

class BrandController extends Controller
{
    public function index(SeoService $seoService)
    {
        $brands = Brand::where('is_active', true)
            ->orderBy('sort_order')
            ->paginate(12);

        $meta = $seoService->generateMetaTags();

        return view('brands.index', compact('brands', 'meta'));
    }

    public function show($slug, SeoService $seoService)
    {
        $brand = Brand::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = $brand->products()
            ->active()
            ->with(['categories', 'images'])
            ->paginate(12);

        $meta = $seoService->generateMetaTags($brand);

        return view('brands.show', compact('brand', 'products', 'meta'));
    }
}
