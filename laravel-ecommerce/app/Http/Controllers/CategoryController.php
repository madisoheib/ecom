<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Services\SeoService;

class CategoryController extends Controller
{
    public function show($slug, Request $request, SeoService $seoService)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with(['children', 'seoMeta'])
            ->firstOrFail();

        $query = $category->products()->active()->with(['brand', 'categories', 'images']);

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Filter by price range
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max')) {
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

        // Get brands available in this category
        $brands = Brand::whereHas('products.categories', function($q) use ($category) {
            $q->where('category_id', $category->id);
        })->where('is_active', true)->orderBy('sort_order')->get();

        // Generate breadcrumbs
        $breadcrumbs = $seoService->generateBreadcrumbs([
            ['name' => __('Categories'), 'url' => route('categories.index')],
            ['name' => $category->name, 'url' => null],
        ]);

        $breadcrumbSchema = $seoService->generateBreadcrumbSchema($breadcrumbs);
        $meta = $seoService->generateMetaTags($category);

        return view('categories.show', compact(
            'category',
            'products',
            'brands',
            'breadcrumbs',
            'breadcrumbSchema',
            'meta'
        ));
    }

    public function index(SeoService $seoService)
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children'])
            ->withCount('products')
            ->orderBy('sort_order')
            ->paginate(12);

        $meta = $seoService->generateMetaTags();

        return view('categories.index', compact('categories', 'meta'));
    }
}
