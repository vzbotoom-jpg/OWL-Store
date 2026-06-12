<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiscountController extends Controller
{
    /**
     * Display discount management page
     */
    public function index()
    {
        return view('admin.pages.discounts.index');
    }

    /**
     * Get discounts data for AJAX datatable
     */
    public function data(Request $request)
    {
        $query = Coupon::query();
        
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }
        
        $discounts = $query->latest()->paginate(10);
        
        $discounts->transform(function($discount) {
            return [
                'id' => $discount->id,
                'code' => $discount->code,
                'name' => $discount->name,
                'type' => $discount->type,
                'value' => $discount->value,
                'discount_display' => $discount->type === 'percentage' ? $discount->value . '%' : 'Rp ' . number_format($discount->value, 0, ',', '.'),
                'min_spend' => $discount->min_spend,
                'min_spend_display' => $discount->min_spend > 0 ? 'Rp ' . number_format($discount->min_spend, 0, ',', '.') : 'Tanpa minimal',
                'max_discount' => $discount->max_discount,
                'usage_limit' => $discount->usage_limit,
                'used_count' => $discount->used_count,
                'per_user_limit' => $discount->per_user_limit,
                'starts_at' => $discount->starts_at,
                'ends_at' => $discount->ends_at,
                'starts_at_formatted' => $discount->starts_at ? $discount->starts_at->format('d/m/Y') : '-',
                'ends_at_formatted' => $discount->ends_at ? $discount->ends_at->format('d/m/Y') : '-',
                'is_active' => $discount->is_active,
                'is_valid' => $discount->is_valid,
                'created_at' => $discount->created_at,
            ];
        });
        
        $stats = [
            'active' => Coupon::where('is_active', true)->where('ends_at', '>=', now())->count(),
            'percentage' => Coupon::where('type', 'percentage')->count(),
            'nominal' => Coupon::where('type', 'nominal')->count(),
            'bogo' => Coupon::where('type', 'bogo')->count(),
            'used' => Coupon::sum('used_count'),
            'unused' => Coupon::sum('usage_limit') - Coupon::sum('used_count'),
            'expired' => Coupon::where('ends_at', '<', now())->count(),
            'total_value' => Coupon::where('type', 'nominal')->sum('value'),
        ];
        
        return response()->json([
            'discounts' => $discounts,
            'pagination' => [
                'current_page' => $discounts->currentPage(),
                'last_page' => $discounts->lastPage(),
                'per_page' => $discounts->perPage(),
                'total' => $discounts->total(),
            ],
            'stats' => $stats
        ]);
    }

    /**
     * Show create discount form
     */
    public function create()
    {
        return view('admin.pages.discounts.create');
    }

    /**
     * Store new discount
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,nominal,bogo',
            'value' => 'required_if:type,percentage,nominal|numeric|min:0',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ]);
        
        $coupon = Coupon::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_spend' => $request->min_spend ?? 0,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'per_user_limit' => $request->per_user_limit ?? 1,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->boolean('is_active'),
        ]);
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Diskon berhasil ditambahkan', 'coupon' => $coupon]);
        }
        
        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil ditambahkan!');
    }

    /**
     * Get discount data for editing
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return response()->json($coupon);
    }

    /**
     * Update discount
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,' . $id,
            'type' => 'required|in:percentage,nominal,bogo',
            'value' => 'required_if:type,percentage,nominal|numeric|min:0',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ]);
        
        $coupon->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_spend' => $request->min_spend ?? 0,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'per_user_limit' => $request->per_user_limit ?? 1,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->boolean('is_active'),
        ]);
        
        return response()->json(['success' => true, 'message' => 'Diskon berhasil diupdate']);
    }

    /**
     * Delete discount
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        
        return response()->json(['success' => true, 'message' => 'Diskon berhasil dihapus']);
    }

    /**
     * Generate random coupon code
     */
    public function generateCode()
    {
        $code = strtoupper(Str::random(8));
        while (Coupon::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(8));
        }
        
        return response()->json(['code' => $code]);
    }
}