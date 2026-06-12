<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = $this->getAllSettings();
        $shippingZones = $this->getShippingZones();
        $banks = $this->getBanks();
        
        return view('admin.pages.settings.index', compact('settings', 'shippingZones', 'banks'));
    }

    /**
     * Update general settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'store_email' => 'required|email',
            'store_phone' => 'required|string|max:20',
            'store_address' => 'nullable|string',
            'store_hours' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('logo')) {
            // Delete old logo
            $oldLogo = setting('store_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            $path = $request->file('logo')->store('settings', 'public');
            $this->saveSetting('store_logo', $path);
        }
        
        $this->saveSetting('store_name', $request->store_name);
        $this->saveSetting('store_description', $request->store_description);
        $this->saveSetting('store_email', $request->store_email);
        $this->saveSetting('store_phone', $request->store_phone);
        $this->saveSetting('store_address', $request->store_address);
        $this->saveSetting('store_hours', $request->store_hours);
        
        Cache::forget('app_settings');
        
        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    /**
     * Update payment settings
     */
    public function updatePayment(Request $request)
    {
        $request->validate([
            'payment_methods' => 'array',
            'midtrans_merchant_id' => 'nullable|string',
            'midtrans_client_key' => 'nullable|string',
            'midtrans_server_key' => 'nullable|string',
            'midtrans_sandbox' => 'boolean',
        ]);
        
        $this->saveSetting('payment_methods', json_encode($request->payment_methods ?? []));
        $this->saveSetting('midtrans_merchant_id', $request->midtrans_merchant_id);
        $this->saveSetting('midtrans_client_key', $request->midtrans_client_key);
        $this->saveSetting('midtrans_server_key', $request->midtrans_server_key);
        $this->saveSetting('midtrans_sandbox', $request->boolean('midtrans_sandbox'));
        
        Cache::forget('app_settings');
        
        return redirect()->back()->with('success', 'Pengaturan pembayaran berhasil disimpan!');
    }

    /**
     * Update shipping settings
     */
    public function updateShipping(Request $request)
    {
        $zones = $request->input('zones', []);
        $couriers = $request->input('couriers', []);
        
        $this->saveSetting('shipping_zones', json_encode($zones));
        $this->saveSetting('shipping_couriers', json_encode($couriers));
        
        Cache::forget('app_settings');
        
        return redirect()->back()->with('success', 'Pengaturan pengiriman berhasil disimpan!');
    }

    /**
     * Update notification settings
     */
    public function updateNotification(Request $request)
    {
        $this->saveSetting('admin_notifications', json_encode($request->admin_notifications ?? []));
        $this->saveSetting('customer_notifications', json_encode($request->customer_notifications ?? []));
        $this->saveSetting('notification_channels', json_encode($request->channels ?? []));
        
        Cache::forget('app_settings');
        
        return redirect()->back()->with('success', 'Pengaturan notifikasi berhasil disimpan!');
    }

    /**
     * Update SEO settings
     */
    public function updateSeo(Request $request)
    {
        $request->validate([
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'keywords' => 'nullable|string',
            'google_analytics' => 'nullable|string',
            'facebook_pixel' => 'nullable|string',
        ]);
        
        $this->saveSetting('meta_title', $request->meta_title);
        $this->saveSetting('meta_description', $request->meta_description);
        $this->saveSetting('keywords', $request->keywords);
        $this->saveSetting('google_analytics', $request->google_analytics);
        $this->saveSetting('facebook_pixel', $request->facebook_pixel);
        
        Cache::forget('app_settings');
        
        return redirect()->back()->with('success', 'Pengaturan SEO berhasil disimpan!');
    }

    /**
     * Display bank accounts page
     */
    public function banks()
    {
        $banks = $this->getBanks();
        return view('admin.pages.settings.bank-accounts', compact('banks'));
    }

    /**
     * Store bank account
     */
    public function storeBank(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);
        
        $banks = $this->getBanks();
        $banks[] = [
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'color' => $this->getBankColor($request->bank_name),
        ];
        
        $this->saveSetting('bank_accounts', json_encode($banks));
        Cache::forget('app_settings');
        
        return redirect()->route('admin.banks.index')->with('success', 'Rekening bank berhasil ditambahkan!');
    }

    /**
     * Delete bank account
     */
    public function destroyBank(Request $request)
    {
        $index = $request->input('index');
        $banks = $this->getBanks();
        
        if (isset($banks[$index])) {
            unset($banks[$index]);
            $this->saveSetting('bank_accounts', json_encode(array_values($banks)));
            Cache::forget('app_settings');
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Helper: Get all settings
     */
    private function getAllSettings()
    {
        $defaults = [
            'store_name' => 'OWL Store',
            'store_description' => 'Furnitur besi premium buatan pengrajin las profesional Yogyakarta',
            'store_email' => 'info@owlstore.com',
            'store_phone' => '+62 838-4402-9190',
            'store_address' => 'Yogyakarta, Indonesia',
            'store_hours' => 'Senin–Sabtu: 08.00–17.00, Minggu: Tutup',
            'meta_title' => 'OWL Store — Furnitur Besi Premium Yogyakarta',
            'meta_description' => 'Toko furnitur besi premium di Yogyakarta. Meja kantor, kursi, rak besi custom dengan garansi 1 tahun.',
        ];
        
        $settings = [];
        foreach ($defaults as $key => $default) {
            $settings[$key] = setting($key, $default);
        }
        
        return $settings;
    }

    /**
     * Helper: Save single setting
     */
    private function saveSetting($key, $value)
    {
        \App\Models\Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Helper: Get shipping zones
     */
    private function getShippingZones()
    {
        $zones = setting('shipping_zones');
        return $zones ? json_decode($zones, true) : [];
    }

    /**
     * Helper: Get bank accounts
     */
    private function getBanks()
    {
        $banks = setting('bank_accounts');
        return $banks ? json_decode($banks, true) : [];
    }

    /**
     * Helper: Get bank color
     */
    private function getBankColor($bankName)
    {
        $colors = [
            'BCA' => 'blue',
            'Mandiri' => 'blue',
            'BRI' => 'green',
            'BNI' => 'blue',
            'CIMB' => 'red',
            'Danamon' => 'blue',
            'Permata' => 'purple',
        ];
        
        foreach ($colors as $name => $color) {
            if (str_contains($bankName, $name)) {
                return $color;
            }
        }
        
        return 'gray';
    }
}