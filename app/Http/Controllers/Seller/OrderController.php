<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    // ─── INDEX: Pesanan yang mengandung produk milik seller ini ──
    public function index(): Response
    {
        $orders = Order::whereHas('items.product', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->with(['buyer', 'items'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Seller/Orders/Index', compact('orders'));
    }

    // ─── SHOW: Detail satu pesanan ────────────────────────────────
    public function show(Order $order): Response
    {
        abort_unless(
            $order->items()->whereHas('product', fn($q) =>
                $q->where('user_id', auth()->id()))->exists(),
            403, 'Bukan pesanan untuk produk Anda.'
        );

        $order->load(['buyer', 'items.product']);

        return Inertia::render('Seller/Orders/Show', compact('order'));
    }

    // ─── VERIFY: Konfirmasi pembayaran → processing ───────────────
    public function verify(Order $order): RedirectResponse
    {
        abort_if($order->status !== Order::STATUS_PAID, 422,
            'Belum ada bukti pembayaran untuk dikonfirmasi.');

        $order->update(['status' => Order::STATUS_PROCESSING]);

        return redirect()->route('seller.orders.show', $order)
            ->with('success', 'Pembayaran dikonfirmasi. Silakan proses pesanan.');
    }

    // ─── REJECT: Tolak pembayaran → kembali ke pending ───────────
    public function reject(Request $request, Order $order): RedirectResponse
    {
        abort_if($order->status !== Order::STATUS_PAID, 422,
            'Tidak ada bukti pembayaran yang bisa ditolak.');

        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $order->update([
            'status'        => Order::STATUS_PENDING,
            'payment_proof' => null,
            'paid_at'       => null,
        ]);

        return redirect()->route('seller.orders.show', $order)
            ->with('error', 'Bukti pembayaran ditolak. Pembeli perlu upload ulang.');
    }

    // ─── SHIP: Tandai pesanan dikirim → shipped ───────────────────
    public function ship(Request $request, Order $order): RedirectResponse
    {
        abort_if($order->status !== Order::STATUS_PROCESSING, 422,
            'Pesanan belum dikonfirmasi pembayarannya.');

        $order->update([
            'status'     => Order::STATUS_SHIPPED,
            'shipped_at' => now(),
        ]);

        return redirect()->route('seller.orders.show', $order)
            ->with('success', 'Pesanan ditandai sudah dikirim.');
    }
}