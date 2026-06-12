<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.pages.settings', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio'   => 'nullable|string|max:500',
        ]);

        $user->update($request->only(['name', 'phone', 'bio']));

        return redirect()->route('user.settings')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return response()->json([
            'success' => true,
            'avatar_url' => Storage::url($path),
            'message' => 'Foto profil berhasil diubah!'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah!']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('user.settings')
            ->with('success', 'Password berhasil diubah!');
    }

    public function updateNotification(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email_notification' => 'boolean',
            'whatsapp_notification' => 'boolean',
        ]);

        // Simpan preferensi notifikasi (perlu migration tambahan)
        $user->update($request->only(['email_notification', 'whatsapp_notification']));

        return response()->json([
            'success' => true,
            'message' => 'Preferensi notifikasi berhasil disimpan!'
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah!']);
        }

        // Hapus semua data terkait
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $user->addresses()->delete();
        $user->orders()->delete();
        $user->reviews()->delete();
        $user->delete();

        Auth::logout();

        return redirect()->route('home')
            ->with('success', 'Akun Anda telah dihapus. Terima kasih telah berbelanja di OWL Store!');
    }
}