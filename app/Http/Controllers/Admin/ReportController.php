<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;

class ReportController extends Controller
{
    /**
     * Display sales report page
     */
    public function sales()
    {
        return view('admin.pages.reports.sales');
    }

    /**
     * Get sales data for charts and tables (AJAX)
     */
    public function salesData(Request $request)
    {
        $startDate = $request->get('start', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end', now()->format('Y-m-d'));
        
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();
        
        // Get daily sales data
        $dailySales = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(DISTINCT user_id) as unique_customers')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Get top products
        $topProducts = OrderItem::whereHas('order', function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->where('status', 'completed');
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('AVG(price) as avg_price')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->name ?? 'Product Deleted',
                    'sold' => $item->total_sold,
                    'revenue' => $item->total_revenue,
                    'rating' => $item->product->rating ?? 5.0,
                ];
            });
        
        // Prepare chart data
        $labels = $dailySales->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d M');
        });
        $revenues = $dailySales->pluck('revenue');
        $orders = $dailySales->pluck('order_count');
        
        // Summary
        $summary = [
            'total_revenue' => $dailySales->sum('revenue'),
            'total_orders' => $dailySales->sum('order_count'),
            'total_items_sold' => OrderItem::whereHas('order', function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])->where('status', 'completed');
            })->sum('quantity'),
            'unique_customers' => $dailySales->sum('unique_customers'),
            'average_order_value' => $dailySales->avg('revenue') ?? 0,
        ];
        
        return response()->json([
            'labels' => $labels,
            'revenues' => $revenues,
            'orders' => $orders,
            'top_products' => $topProducts,
            'daily_sales' => $dailySales->map(function($item) {
                return [
                    'date' => \Carbon\Carbon::parse($item->date)->format('d/m/Y'),
                    'order_count' => $item->order_count,
                    'items_sold' => OrderItem::whereHas('order', function($q) use ($item) {
                        $q->whereDate('created_at', $item->date)->where('status', 'completed');
                    })->sum('quantity'),
                    'revenue' => $item->revenue,
                    'average' => $item->order_count > 0 ? $item->revenue / $item->order_count : 0,
                ];
            }),
            'summary' => $summary
        ]);
    }

    /**
     * Export sales report to PDF or Excel
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'excel');
        $startDate = $request->get('start', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end', now()->format('Y-m-d'));
        
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();
        
        $orders = Order::whereBetween('created_at', [$start, $end])
            ->with('user', 'items')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $summary = [
            'total_revenue' => $orders->where('status', 'completed')->sum('total'),
            'total_orders' => $orders->count(),
            'total_items' => $orders->sum(function($o) { return $o->items->sum('quantity'); }),
            'period' => $start->format('d M Y') . ' - ' . $end->format('d M Y'),
        ];
        
        if ($type === 'pdf') {
            $pdf = Pdf::loadView('admin.pages.reports.pdf-export', compact('orders', 'summary', 'startDate', 'endDate'));
            return $pdf->download('sales-report-' . now()->format('Y-m-d') . '.pdf');
        }
        
        // Excel export
        return Excel::download(new SalesExport($orders, $summary), 'sales-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Export top products to Excel
     */
    public function exportTopProducts(Request $request)
    {
        $startDate = $request->get('start', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end', now()->format('Y-m-d'));
        
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end = \Carbon\Carbon::parse($endDate)->endOfDay();
        
        $topProducts = OrderItem::whereHas('order', function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->where('status', 'completed');
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->product->name ?? 'Product Deleted',
                    'category' => $item->product->category->name ?? '-',
                    'sold' => $item->total_sold,
                    'revenue' => $item->total_revenue,
                ];
            });
        
        return Excel::download(new TopProductsExport($topProducts), 'top-products-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Get monthly revenue for dashboard chart
     */
    public function getMonthlyRevenue($year = null)
    {
        $year = $year ?? now()->year;
        
        $monthlyData = Order::whereYear('created_at', $year)
            ->where('status', 'completed')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('revenue', 'month')
            ->toArray();
        
        $revenues = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenues[] = $monthlyData[$i] ?? 0;
        }
        
        return response()->json($revenues);
    }
}