<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Services\SeoService;

class ProductController extends Controller
{
    public function index(Request $request, SeoService $seoService)
    {
        $query = Product::active()->with(['brand', 'categories', 'images']);

        // Filter by search term
        if ($request->has('search') && $request->search !== null && $request->search !== '') {
            $search = $request->search;
            $locale = app()->getLocale();
            $query->where(function($q) use ($search, $locale) {
                if (config('database.default') === 'sqlite') {
                    $q->whereRaw("JSON_EXTRACT(name, '$.{$locale}') LIKE ?", ["%{$search}%"])
                      ->orWhereRaw("JSON_EXTRACT(description, '$.{$locale}') LIKE ?", ["%{$search}%"])
                      ->orWhere('sku', 'LIKE', "%{$search}%");
                } else {
                    $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.{$locale}')) LIKE ?", ["%{$search}%"])
                      ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(description, '$.{$locale}')) LIKE ?", ["%{$search}%"])
                      ->orWhere('sku', 'LIKE', "%{$search}%");
                }
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category !== null && $request->category !== '') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand !== null && $request->brand !== '') {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Filter by price range
        if ($request->has('price_min') && $request->price_min !== null && $request->price_min !== '') {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max !== null && $request->price_max !== '') {
            $query->where('price', '<=', $request->price_max);
        }

        // Sorting
        $sort = $request->get('sort', 'name');
        $locale = app()->getLocale();
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            default:
                if (config('database.default') === 'sqlite') {
                    $query->orderByRaw("JSON_EXTRACT(name, '$.{$locale}')");
                } else {
                    $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.{$locale}'))");
                }
                break;
        }

        $products = $query->paginate(12);

        // Get filter options
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $brands = Brand::where('is_active', true)->orderBy('sort_order')->get();

        $meta = $seoService->generateMetaTags();

        return view('products.index', compact('products', 'categories', 'brands', 'meta'));
    }

    public function show($categorySlug, $productSlug, SeoService $seoService)
    {
        $product = Product::where('slug', $productSlug)
            ->where('is_active', true)
            ->with(['brand', 'categories', 'images', 'seoMeta'])
            ->firstOrFail();

        // Increment view count
        $product->increment('views_count');

        // Get related products from same category
        $relatedProducts = Product::active()
            ->whereHas('categories', function($q) use ($product) {
                $q->whereIn('category_id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->with(['brand', 'categories', 'images'])
            ->limit(4)
            ->get();

        // Generate breadcrumbs
        $category = $product->categories->first();
        $breadcrumbs = $seoService->generateBreadcrumbs([
            ['name' => __('Products'), 'url' => route('products.index')],
            ['name' => $category?->name, 'url' => route('categories.show', $category?->slug)],
            ['name' => $product->name, 'url' => null],
        ]);

        // Generate Schema markup
        $productSchema = $seoService->generateProductSchema($product);
        $breadcrumbSchema = $seoService->generateBreadcrumbSchema($breadcrumbs);

        // Get user location from IP
        $userLocation = get_user_country_from_ip();
        $countries = get_countries_list();
        $cities = get_cities_for_country($userLocation['country_code']);

        $meta = $seoService->generateMetaTags($product);

        return view('products.show-simple', compact(
            'product',
            'relatedProducts',
            'breadcrumbs',
            'productSchema',
            'breadcrumbSchema',
            'userLocation',
            'countries',
            'cities',
            'meta'
        ));
    }
}
