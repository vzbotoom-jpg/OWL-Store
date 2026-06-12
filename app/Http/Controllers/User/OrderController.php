<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Order::where('user_id', $user->id)->with('items.product');
        
        // Filter by status
        if ($request->has('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }
        
        // Filter by date
        if ($request->has('date_range')) {
            switch ($request->date_range) {
                case 'week':
                    $query->where('created_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', now()->subYear());
                    break;
            }
        }
        
        $orders = $query->latest()->paginate(10);
        
        // Statistics
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'total_spent' => Order::where('user_id', $user->id)->where('status', 'completed')->sum('total'),
            'pending' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];
        
        return view('user.pages.orders', compact('orders', 'stats'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.product', 'user'])
            ->findOrFail($id);
            
        return view('user.pages.order-detail', compact('order'));
    }

    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'processed'])
            ->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Restore stock
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->increment('stock', $item->qty);
            }
            
            $order->update(['status' => 'cancelled']);
            
            DB::commit();
            
            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibatalkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pesanan. Silakan coba lagi.');
        }
    }

    public function confirmReceived($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'shipped')
            ->findOrFail($id);
        
        $order->update(['status' => 'completed']);
        
        return redirect()->route('user.orders.show', $order->id)
            ->with('success', 'Terima kasih! Pesanan telah dikonfirmasi diterima.');
    }

    public function tracking($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->whereNotNull('resi')
            ->findOrFail($id);
        
        // Integrasi tracking dengan berbagai kurir
        $trackingData = $this->getTrackingInfo($order->resi, $order->shipping_courier ?? 'jne');
        
        return response()->json($trackingData);
    }

    private function getTrackingInfo($resi, $courier)
    {
        // TODO: Integrasi API tracking kurir (JNE, J&T, SiCepat, dll)
        // Sementara return data dummy
        return [
            'status' => 'in_transit',
            'history' => [
                ['date' => now()->subDays(2), 'status' => 'Paket telah diterima oleh kurir'],
                ['date' => now()->subDays(1), 'status' => 'Paket sedang dalam perjalanan'],
                ['date' => now(), 'status' => 'Paket tiba di kota tujuan'],
            ],
            'estimated_delivery' => now()->addDays(1)->format('d M Y'),
        ];
    }

    public function invoice($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        
        // Generate PDF invoice
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.pages.invoice', compact('order'));
        
        return $pdf->download('invoice-' . $order->id . '.pdf');
    }
}