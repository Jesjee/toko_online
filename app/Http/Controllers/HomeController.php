<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home', [
            'totalProduk'   => Product::where('status', 'active')->count(),
            'totalPenjual'  => User::where('role', 'seller')->where('is_active', true)->count(),
            'categories'    => Category::where('is_active', true)
                                ->orderBy('sort_order')
                                ->get(['id', 'name', 'icon', 'slug']),
            'produkTerbaru' => Product::where('status', 'active')
                                ->with('categories')
                                ->latest()->take(4)->get(),
        ]);
    }
}