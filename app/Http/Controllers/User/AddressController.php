<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        
        return view('user.pages.addresses', compact('addresses'));
    }

    public function create()
    {
        return view('user.pages.address-form');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'label'       => 'required|string|max:50',
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string',
            'city'        => 'required|string|max:100',
            'province'    => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default'  => 'boolean',
        ]);
        
        DB::beginTransaction();
        try {
            // Jika alamat baru dijadikan default, unset default lainnya
            if ($request->is_default) {
                $user->addresses()->update(['is_default' => false]);
            }
            
            $address = $user->addresses()->create($request->all());
            
            // Jika ini alamat pertama, jadikan default
            if ($user->addresses()->count() === 1) {
                $address->update(['is_default' => true]);
            }
            
            DB::commit();
            
            return redirect()->route('user.addresses')
                ->with('success', 'Alamat berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan alamat. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        return view('user.pages.address-form', compact('address'));
    }

    public function update(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'label'       => 'required|string|max:50',
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string',
            'city'        => 'required|string|max:100',
            'province'    => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default'  => 'boolean',
        ]);
        
        DB::beginTransaction();
        try {
            if ($request->is_default && !$address->is_default) {
                Auth::user()->addresses()->update(['is_default' => false]);
            }
            
            $address->update($request->all());
            
            DB::commit();
            
            return redirect()->route('user.addresses')
                ->with('success', 'Alamat berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui alamat.');
        }
    }

    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        if ($address->is_default) {
            return back()->with('error', 'Tidak dapat menghapus alamat utama. Pilih alamat lain sebagai utama terlebih dahulu.');
        }
        
        $address->delete();
        
        return redirect()->route('user.addresses')
            ->with('success', 'Alamat berhasil dihapus!');
    }

    public function setDefault($id)
    {
        $user = Auth::user();
        
        DB::beginTransaction();
        try {
            $user->addresses()->update(['is_default' => false]);
            
            $address = Address::where('user_id', $user->id)->findOrFail($id);
            $address->update(['is_default' => true]);
            
            DB::commit();
            
            return redirect()->route('user.addresses')
                ->with('success', 'Alamat utama berhasil diubah!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengubah alamat utama.');
        }
    }

    public function getCities(Request $request)
    {
        // Integrasi dengan API RajaOngkir atau sejenisnya
        $cities = [
            ['id' => 1, 'name' => 'Yogyakarta'],
            ['id' => 2, 'name' => 'Sleman'],
            ['id' => 3, 'name' => 'Bantul'],
            ['id' => 4, 'name' => 'Jakarta'],
            ['id' => 5, 'name' => 'Bandung'],
            ['id' => 6, 'name' => 'Surabaya'],
        ];
        
        return response()->json($cities);
    }
}