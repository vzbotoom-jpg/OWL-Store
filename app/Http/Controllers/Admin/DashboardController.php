<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders'   => Order::count(),
            'total_users'    => User::where('is_admin', false)->count(),
            'total_reviews'  => Review::count(),
            'total_revenue'  => Order::where('status', 'completed')->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processed_orders' => Order::where('status', 'processed')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
        ];

        $recent_orders = Order::with('user')->latest()->take(5)->get();
        $lowStockProducts = Product::where('stock', '<=', 5)->where('stock', '>', 0)->get();

        return view('admin.pages.dashboard', compact('stats', 'recent_orders', 'lowStockProducts'));
    }

    /**
     * Get real-time dashboard data for AJAX polling
     */
    public function getDashboardData(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Stats
        $stats = [
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_users' => User::where('is_admin', false)->count(),
            'total_revenue_trend' => $this->getRevenueTrend(),
            'total_revenue_trend_class' => 'text-xs text-green-600 font-semibold',
        ];
        
        // Monthly revenue data for chart
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRevenue[] = Order::where('status', 'completed')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->sum('total');
        }
        
        // Order status counts
        $orderStatuses = [
            Order::where('status', 'pending')->count(),
            Order::where('status', 'processed')->count(),
            Order::where('status', 'shipped')->count(),
            Order::where('status', 'completed')->count(),
            Order::where('status', 'cancelled')->count(),
        ];
        
        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user->name ?? 'Guest',
                    'customer_initial' => strtoupper(substr($order->user->name ?? 'G', 0, 1)),
                    'total' => $order->total,
                    'status' => $order->status,
                    'status_label' => ucfirst($order->status),
                    'formatted_date' => $order->created_at->format('d M Y'),
                ];
            });
        
        // Low stock products
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'price' => $product->price,
                    'image' => $product->image,
                ];
            });
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'charts' => [
                'revenue_data' => $monthlyRevenue,
                'order_status_data' => $orderStatuses,
            ],
            'recent_orders' => $recentOrders,
            'low_stock' => $lowStockProducts,
        ]);
    }

    /**
     * Calculate revenue trend percentage
     */
    private function getRevenueTrend()
    {
        $currentMonth = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('total');
        
        $lastMonth = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('total');
        
        if ($lastMonth == 0) {
            return $currentMonth > 0 ? '+100%' : '0%';
        }
        
        $percentage = (($currentMonth - $lastMonth) / $lastMonth) * 100;
        $sign = $percentage >= 0 ? '+' : '';
        
        return $sign . round($percentage, 1) . '%';
    }
}