<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SecurityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get active sessions (devices)
        $sessions = $this->getActiveSessions();
        
        return view('user.pages.security', compact('user', 'sessions'));
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah!']);
        }
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        // Logout from other devices (optional)
        if ($request->logout_other_devices) {
            Auth::logoutOtherDevices($request->password);
        }
        
        // Send email notification
        // Mail::to($user->email)->send(new PasswordChangedNotification($user));
        
        return redirect()->route('user.security')
            ->with('success', 'Password berhasil diubah!');
    }

    public function enableTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);
        
        // Generate secret key for 2FA
        $secret = $this->generateTwoFactorSecret();
        
        $user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => $secret,
            'two_factor_phone' => $request->phone,
        ]);
        
        // Send verification code via WhatsApp/SMS
        $this->sendVerificationCode($user->phone);
        
        return redirect()->route('user.security')
            ->with('success', 'Verifikasi dua langkah berhasil diaktifkan. Silakan verifikasi kode yang dikirim.');
    }

    public function verifyTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'code' => 'required|string|size:6',
        ]);
        
        if ($this->verifyTwoFactorCode($user->two_factor_secret, $request->code)) {
            $user->update(['two_factor_verified' => true]);
            
            return redirect()->route('user.security')
                ->with('success', 'Verifikasi dua langkah berhasil diverifikasi!');
        }
        
        return back()->withErrors(['code' => 'Kode verifikasi salah!']);
    }

    public function disableTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'password' => 'required|string',
        ]);
        
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah!']);
        }
        
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_phone' => null,
            'two_factor_verified' => false,
        ]);
        
        return redirect()->route('user.security')
            ->with('success', 'Verifikasi dua langkah telah dinonaktifkan.');
    }

    public function revokeSession($sessionId)
    {
        // Logout specific session/device
        // Implementation depends on session driver
        
        return redirect()->route('user.security')
            ->with('success', 'Session berhasil dihapus.');
    }

    public function activityLog()
    {
        $activities = Auth::user()->activities()->latest()->paginate(20);
        
        return view('user.pages.activity-log', compact('activities'));
    }

    private function getActiveSessions()
    {
        // Get active sessions from database/cache
        return collect([
            (object)[
                'device' => 'Chrome on Windows',
                'ip' => '192.168.1.1',
                'location' => 'Yogyakarta, Indonesia',
                'last_active' => now()->subMinutes(5),
                'current' => true,
            ],
            (object)[
                'device' => 'Safari on iPhone',
                'ip' => '192.168.1.2',
                'location' => 'Yogyakarta, Indonesia',
                'last_active' => now()->subHours(2),
                'current' => false,
            ],
        ]);
    }

    private function generateTwoFactorSecret()
    {
        return strtoupper(substr(md5(uniqid()), 0, 16));
    }

    private function sendVerificationCode($phone)
    {
        // Integrasi dengan API WhatsApp/SMS gateway
        // Contoh: Kirim kode via WhatsApp Business API
        $code = rand(100000, 999999);
        Cache::put('2fa_code_' . $phone, $code, 300); // Expire 5 menit
        
        // TODO: Kirim via WhatsApp API
        // WhatsApp::send($phone, "Kode verifikasi OWL Store: $code");
    }

    private function verifyTwoFactorCode($secret, $code)
    {
        $cachedCode = Cache::get('2fa_code_' . Auth::user()->phone);
        return $cachedCode && $cachedCode == $code;
    }
}