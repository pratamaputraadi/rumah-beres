<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Tambahkan ini jika belum ada

class AuthController extends Controller
{
    // --- TAMPILAN HALAMAN ---

    public function showRegisterChoice()
    {
        return view('auth.register_choice');
    }

    public function showRegisterCustomer()
    {
        return view('auth.register_customer');
    }

    public function showRegisterTechnician()
    {
        return view('auth.register_technician');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    // --- PROSES REGISTER CUSTOMER ---
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'customer',
            'is_verified' => 1
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan Login.');
    }

    // --- PROSES REGISTER TEKNISI ---
    public function storeTechnician(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'specialization' => 'required',
            'ktp' => 'required|image|max:2048',          // KTP Wajib
            'certificate' => 'nullable|image|max:2048',  // Sertifikat Opsional
        ]);

        // 2. Upload KTP
        $ktpPath = null;
        if ($request->hasFile('ktp')) {
            $ktpPath = $request->file('ktp')->store('uploads/ktp', 'public');
        }

        // 3. Upload Sertifikat
        $certPath = null;
        if ($request->hasFile('certificate')) {
            $certPath = $request->file('certificate')->store('uploads/certificates', 'public');
        }

        // 4. Simpan ke Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'technician',
            'specialization' => $request->specialization,
            'address' => $request->address,
            'ktp_photo' => $ktpPath,
            'certificate_photo' => $certPath,
            'is_verified' => false // Default false (menunggu admin)
        ]);

        // 5. Redirect ke Login
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Tunggu verifikasi admin.');
    }

    // --- PROSES LOGIN (SUDAH DIPERBAIKI LOGIKA VERIFIKASI) ---
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $request->session()->regenerate();

            // === LOGIKA PENCEGAHAN LOGIN TEKNISI PENDING ===
            if ($user->role === 'technician' && !$user->is_verified) {
                // Batalkan sesi login
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Kembalikan error
                return back()->withErrors([
                    'email' => 'Akun teknisi Anda belum diverifikasi oleh Admin. Mohon tunggu proses persetujuan.',
                ]);
            }
            // ===============================================

            // Redirect jika sudah lolos pengecekan
            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            } elseif ($user->role === 'technician') {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // --- PROSES LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
