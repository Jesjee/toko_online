<?php
 
namespace App\Http\Controllers;
 
use Inertia\Inertia;   // ← tambahkan baris import ini
use Inertia\Response;
 
class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home', [
            'totalProduk'   => 0,
            'totalPenjual'  => 0,
        ]);
    }
}