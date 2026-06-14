<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function dashboard(Request $request): Response
    {
        $user = $request->user();

        $totalPesanan = Order::where('user_id', $user->id)->count();
        $pesananPending = Order::where('user_id', $user->id)
                            ->where('status', Order::STATUS_PENDING)->count();
        $totalBelanjaBulanIni = Order::where('user_id', $user->id)
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->whereNotIn('status', [Order::STATUS_CANCELLED])
                            ->sum('total_amount');

        return Inertia::render('Buyer/Dashboard', compact(
            'totalPesanan', 'pesananPending', 'totalBelanjaBulanIni'
        ));
    }

    public function index(Request $request): Response
    {
        $status = $request->get('status');

        $orders = Order::where('user_id', $request->user()->id)
                        ->with('items')
                        ->when($status, fn($q) => $q->where('status', $status))
                        ->latest()
                        ->paginate(10);

        return Inertia::render('Orders/Index', compact('orders', 'status'));
    }

    public function show(Request $request, Order $order): Response
    {
        abort_if($order->user_id !== $request->user()->id, 403, 'Bukan pesanan Anda.');

        $order->load(['items.product', 'buyer']);

        return Inertia::render('Orders/Show', compact('order'));
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        abort_if($order->user_id !== $request->user()->id, 403, 'Bukan pesanan Anda.');

        abort_if($order->status !== Order::STATUS_PENDING, 403, 'Pesanan tidak bisa dibatalkan.');

        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->qty);
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}