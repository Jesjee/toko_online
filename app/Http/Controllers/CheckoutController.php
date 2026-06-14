<?php 
namespace App\Http\Controllers; 
  
use App\Models\Order; 
use App\Models\Product; 
use Illuminate\Http\RedirectResponse; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use Inertia\Inertia; 
use Inertia\Response; 
  
class CheckoutController extends Controller 
{ 
    // ─── index(): Tampilkan halaman checkout ────────────────────── 
    public function index(Request $request): Response 
    { 
        // Di P8 ini, kirim produk sample untuk simulasi checkout. 
        // Di P10 setelah cart ada, items diambil dari cart buyer. 
        $sampleItems = Product::where('status', 'active') 
                               ->take(2)->get() 
                               ->map(fn($p) => [ 
                                   'product_id'    => $p->id, 
                                   'product_name'  => $p->name, 
                                   'price'         => $p->price, 
                                   'qty'           => 1, 
                                   'product_image' => $p->image, 
                               ]); 
  
        return Inertia::render('Checkout', [ 
            'items' => $sampleItems, 
            'total' => $sampleItems->sum(fn($i) => $i['price'] * $i['qty']), 
        ]); 
    } 
  
    // ─── store(): Proses checkout ───────────────────────────────── 
    public function store(Request $request): RedirectResponse 
    { 
        $validated = $request->validate([ 
            'shipping_address'   => 'required|string|max:500', 
            'phone'              => 'required|string|max:20', 
            'notes'              => 'nullable|string|max:500', 
            'items'              => 'required|array|min:1', 
            'items.*.product_id' => 'required|exists:products,id', 

            'items.*.qty'        => 'required|integer|min:1', 
        ]); 
  
        try { 
            $order = DB::transaction(function () use ($validated, $request) 
{ 
  
                $total      = 0; 
                $orderItems = []; 
  
                // ─── Langkah 1: Validasi stok + siapkan snapshot ── 
                foreach ($validated['items'] as $item) { 
                    // lockForUpdate() kunci baris agar tidak ada 
                    // dua buyer yang membeli stok yang sama bersamaan 
                    $product = Product::lockForUpdate() 
                                      ->findOrFail($item['product_id']); 
  
                    if ($product->stock < $item['qty']) { 
                        throw new \Exception( 
                            'Stok ' . $product->name . ' tidak mencukupi. ' 
. 
                            'Tersisa ' . $product->stock . ' unit.' 
                        ); 
                    } 
  
                    // Kurangi stok 
                    $product->decrement('stock', $item['qty']); 
  
                    // Hitung total 
                    $total += $product->price * $item['qty']; 
  
                    // Siapkan snapshot 
                    $orderItems[] = [ 
                        'product_id'    => $product->id, 
                        'product_name'  => $product->name,  // snapshot nama 
                        'price'         => $product->price, // snapshot harga 
                        'qty'           => $item['qty'], 
                        'product_image' => $product->image, 
                    ]; 
                } 
  
                // ─── Langkah 2: Buat order header ───────────────── 
                $order = Order::create([ 
                    'user_id'          => $request->user()->id, 
                    'order_number'     => Order::generateNumber(), 
                    'status'           => Order::STATUS_PENDING, 
                    'total_amount'     => $total, 
                    'shipping_address' => $validated['shipping_address'], 
                    'phone'            => $validated['phone'], 
                    'notes'            => $validated['notes'] ?? null, 
                ]); 
  
                // ─── Langkah 3: Buat semua order_items sekaligus ── 
                $order->items()->createMany($orderItems); 
  
                return $order; 
            }); // ← jika ada exception di atas, semua di-rollback 
  
            return redirect()->route('orders.show', $order) 
                             ->with('success', 'Pesanan berhasil dibuat! ' . $order->order_number); 
  
        } catch (\Exception $e) { 
            // Exception = rollback otomatis, stok kembali, order tidak jadi 
            return back()->withErrors(['checkout' => $e->getMessage()]) 
                         ->withInput(); 
        } 
    } 
} 
