<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Penting untuk upload file
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index() // Halaman Utama / Job Requests (Incoming)
    {
        $user = Auth::user();

        // SKENARIO 1: Jika ADMIN yang login
        if ($user->role === 'admin') {

            // 1. Hitung Jumlah User per Role (Untuk 3 Kartu Terpisah)
            $countAdmin     = User::where('role', 'admin')->count();
            $countCustomer  = User::where('role', 'customer')->count();
            $countTech      = User::where('role', 'technician')->count();

            // 2. Hitung Total Order (Hanya angkanya saja)
            $totalOrders    = Order::count();

            // 3. Hitung Jumlah Teknisi Pending (Untuk Kartu Peringatan)
            $pendingCount   = User::where('role', 'technician')->where('is_verified', 0)->count();

            // Kirim semua variabel ke view admin.dashboard
            return view('admin.dashboard', compact(
                'countAdmin',
                'countCustomer',
                'countTech',
                'totalOrders',
                'pendingCount'
            ));
        }

        // SKENARIO 2: Jika TEKNISI yang login (Hanya Incoming/Available Requests)
        if ($user->role === 'technician') {

            // Query untuk mendapatkan INCOMING JOB REQUESTS
            // Status pending DAN (technician_id kosong ATAU technician_id = user->id)
            $available_requests = Order::where('status', 'pending')
                ->where(function ($query) use ($user) {
                    $query->whereNull('technician_id') // Job umum yang belum dialokasikan
                        ->orWhere('technician_id', $user->id); // Job yang dialokasikan langsung kepadanya
                })
                ->get();

            // Hitungan untuk kartu (perlu dihitung semua)
            $accepted_jobs_count = Order::where('technician_id', $user->id)
                ->where('status', '!=', 'pending') // Semua yang sudah di-accept
                ->count();
            $cancelled_jobs_count = Order::where('technician_id', $user->id)->where('status', 'cancelled')->count();


            return view('technician.dashboard', [
                'available_requests' => $available_requests,
                'accepted_jobs_count' => $accepted_jobs_count,
                'available_requests_count' => count($available_requests),
                'cancelled_jobs_count' => $cancelled_jobs_count
            ]);
        }

        // SKENARIO 3: Jika CUSTOMER yang login (Default)
        return view('customer.dashboard', [
            'my_orders' => Order::where('customer_id', $user->id)->get()
        ]);
    }

    // FUNGSI BARU: Menampilkan Accepted Jobs (untuk menu Request)
    public function acceptedJobs()
    {
        $user = Auth::user();
        if ($user->role !== 'technician') {
            return redirect()->route('dashboard');
        }

        // Accepted Jobs (Semua status setelah 'pending' dan dialokasikan ke user ini)
        $my_jobs = Order::where('technician_id', $user->id)
            ->where('status', '!=', 'pending') // Semua job yang sudah di-accept (statusnya bukan pending)
            ->get();

        // Kirim semua variabel yang dibutuhkan oleh dashboard view (untuk kartu)
        $accepted_jobs_count = count($my_jobs);

        // Hitung Incoming Requests untuk kartu
        $available_requests_count = Order::where('status', 'pending')
            ->where(function ($query) use ($user) {
                $query->whereNull('technician_id')
                    ->orWhere('technician_id', $user->id);
            })->count();

        $cancelled_jobs_count = Order::where('technician_id', $user->id)->where('status', 'cancelled')->count();

        return view('technician.accepted_jobs', [ // Kita buat view terpisah untuk Accepted Jobs
            'my_jobs' => $my_jobs,
            'accepted_jobs_count' => $accepted_jobs_count,
            'available_requests_count' => $available_requests_count,
            'cancelled_jobs_count' => $cancelled_jobs_count
        ]);
    }


    // =========================================
    // FITUR PROFILE TECHNICIAN (LANJUTAN)
    // =========================================

    // 1. Tampilkan Halaman Profile Teknisi
    public function technicianProfile()
    {
        $user = Auth::user();
        if ($user->role !== 'technician') {
            abort(403);
        }
        return view('technician.profile', compact('user'));
    }

    // 2. Proses Update Profile Teknisi
    public function updateTechnicianProfile(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name'      => 'required|string|max:255',
            'bio'       => 'nullable|string|max:500',
            'address'   => 'nullable|string|max:500',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ktp'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'certificate' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:6',
        ]);

        $needsReverification = false;

        // --- Update Avatar ---
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // --- Update KTP ---
        if ($request->hasFile('ktp')) {
            if ($user->ktp_photo && Storage::disk('public')->exists($user->ktp_photo)) {
                Storage::disk('public')->delete($user->ktp_photo);
            }
            $user->ktp_photo = $request->file('ktp')->store('uploads/ktp', 'public');
            $needsReverification = true;
        }

        // --- Update Certificate ---
        if ($request->hasFile('certificate')) {
            if ($user->certificate_photo && Storage::disk('public')->exists($user->certificate_photo)) {
                Storage::disk('public')->delete($user->certificate_photo);
            }
            $user->certificate_photo = $request->file('certificate')->store('uploads/certificates', 'public');
            $needsReverification = true;
        }

        // --- Update Text Fields ---
        $user->name = $request->name;
        $user->bio = $request->bio;
        $user->address = $request->address;

        // Update Password
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Jika ada dokumen penting yang diubah, set is_verified ke false
        if ($needsReverification) {
            $user->is_verified = false;
        }

        $user->save();

        if ($needsReverification) {
            return back()->with('success', 'Profil dan dokumen berhasil diupdate! Akun Anda akan dinonaktifkan sementara untuk Verifikasi Ulang oleh Admin.');
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
