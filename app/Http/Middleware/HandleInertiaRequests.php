<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),

            'auth' => [
                'user' => $user ? [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role, // -- dipakai navbar Vue
                    'avatar'=> $user->avatar,
                    // cartCount dan unreadNotifications akan ditambahkan
                    // di pertemuan berikutnya setelah tabel cart_items ada
                ] : null,
            ],

            // Flash message - ditampilkan di AppLayout.vue
            'flash' => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
            ],
        ];
    }
}