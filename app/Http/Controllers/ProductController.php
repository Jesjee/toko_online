<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    // ─── INDEX: Katalog produk publik ─────────────────────────────
    public function index(Request $request): Response
    {
        $query = Product::where('status', 'active')
            ->with(['categories', 'seller'])
            ->withCount('reviews');

        if ($request->category) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ─── Latihan 1: Sort ──────────────────────────────────────
        $sort = $request->get('sort', 'latest');

        match($sort) {
            'price_asc'  => $query->orderByRaw('CAST(price AS DECIMAL(15,2)) ASC'),
            'price_desc' => $query->orderByRaw('CAST(price AS DECIMAL(15,2)) DESC'),
            'popular'    => $query->orderBy('views', 'desc'),
            default      => $query->latest(),
        };

        return Inertia::render('Products/Index', [
            'products'   => $query->paginate(12)->withQueryString(),
            'categories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
            'filters'    => $request->only('search', 'category', 'sort'),
        ]);
    }

    // ─── SHOW: Detail produk ──────────────────────────────────────
    public function show(Product $product): Response
    {
        $product->increment('views');

        $product->load([
            'categories',
            'seller',
            'reviews' => fn($q) => $q->with('user:id,name,avatar')->latest(),
        ]);

        return Inertia::render('Products/Show', [
            'product'    => $product,
            'produkLain' => Product::where('user_id', $product->user_id)
                            ->where('id', '!=', $product->id)
                            ->where('status', 'active')
                            ->take(4)->get(),
        ]);
    }
}