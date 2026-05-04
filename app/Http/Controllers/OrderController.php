<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function dashboard(Request $request): Response
    {
        return Inertia::render('Buyer/Dashboard', [
            'user' => $request->user(),
        ]);
    }
}