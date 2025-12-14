<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Wajib ada untuk hapus/simpan file
use Illuminate\Support\Facades\Hash;    // Wajib ada untuk enkripsi password
use App\Models\Order;
use App\Models\Category;
use App\Models\User; // PENTING: Tambahkan ini agar bisa mencari data teknisi

class CustomerController extends Controller
{
    // 1. HALAMAN PROFILE
    public function profile()
    {
        return view('customer.profile', ['user' => Auth::user()]);
    }

    // 2. HALAMAN ORDERS
    public function orders()
    {
        $orders = Order::where('customer_id', Auth::id())->get();
        return view('customer.orders', compact('orders'));
    }

    // 3. HALAMAN CATEGORIES
    public function categories()
    {
        // Asumsi model Category::all() bekerja
        $categories = Category::all();
        return view('customer.categories', compact('categories'));
    }

    // 4. UPDATE PROFILE (LOGIKA LENGKAP)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // --- A. VALIDASI ---
        $request->validate([
            'name'      => 'required|string|max:255',
            'address'   => 'nullable|string',
            'bio'       => 'nullable|string|max:500',
            // Password opsional, tapi kalau diisi minimal 6 digit & harus confirmed
            'password'  => 'nullable|min:6|confirmed',
            // Validasi gambar (maks 2MB)
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // --- B. UPDATE DATA TEKS ---
        $user->name     = $request->name;
        $user->address = $request->address;
        $user->bio     = $request->bio;

        // --- C. UPDATE PASSWORD (JIKA DIISI) ---
        if ($request->filled('password')) {
            // Hash password baru sebelum disimpan
            $user->password = Hash::make($request->password);
        }

        // --- D. UPDATE FOTO PROFIL (AVATAR) ---
        if ($request->hasFile('avatar')) {
            // 1. Hapus foto lama biar hemat penyimpanan (jika ada file fisik)
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // 2. Simpan foto baru ke folder 'avatars'
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // --- E. UPDATE KTP ---
        if ($request->hasFile('ktp_photo')) {
            // 1. Hapus KTP lama
            if ($user->ktp_photo && Storage::disk('public')->exists($user->ktp_photo)) {
                Storage::disk('public')->delete($user->ktp_photo);
            }
            // 2. Simpan KTP baru ke folder 'documents'
            $path = $request->file('ktp_photo')->store('documents', 'public');
            $user->ktp_photo = $path;
        }

        // --- F. SIMPAN PERUBAHAN ---
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // 5. SHOW CATEGORY (Menampilkan List Teknisi per Kategori)
    public function showCategory($slug)
    {
        // 1. Mapping Slug URL ke Nama Spesialisasi di Database
        $mapping = [
            'cooling'       => 'Cooling',
            'entertainment' => 'Entertainment',
            'laundry'       => 'Laundry',
            'kitchen'       => 'Kitchen',
            'computing'     => 'Computing',
            'water'         => 'Water',
        ];

        $categoryName = $mapping[$slug] ?? 'Cooling';

        // 2. Judul Lengkap untuk Tampilan Header
        $titles = [
            'Cooling'       => 'Cooling & Air Treatment',
            'Entertainment' => 'Entertainment Appliances',
            'Laundry'       => 'Laundry Appliances',
            'Kitchen'       => 'Kitchen Appliances',
            'Computing'     => 'Computing & Home Office',
            'Water'         => 'Water Pumps & Heaters',
        ];
        $displayTitle = $titles[$categoryName] ?? $categoryName;

        // 3. Cari Teknisi di Database
        $technicians = User::where('role', 'technician')
            ->where('specialization', $categoryName)
            ->where('is_verified', 1)
            ->get();

        // >>> PERBAIKAN VIEW NAME: Menggunakan customer.category_details <<<
        return view('customer.category_details', compact('technicians', 'displayTitle', 'slug'));
    }

    // 6. FUNGSI BARU: Menampilkan Profile Teknisi Publik (dengan Rating dan Bio)
    public function showTechnicianProfile($slug, $id)
    {
        // 1. Ambil data teknisi yang diverifikasi berdasarkan ID dan Specialization
        $technician = User::where('role', 'technician')
            ->where('id', $id)
            ->where('specialization', $slug) // Pastikan slug cocok dengan specialization
            ->where('is_verified', true)
            ->firstOrFail();

        // 2. --- Data Dummy Ratings ---
        // Ganti ini dengan logika pengambilan data Rating yang sesungguhnya!
        $ratings = [
            (object)['user' => 'Adi Pratama Putra', 'city' => 'Karawang, Jawa Barat', 'rating' => 4.8, 'review' => 'Luar biasa puas AC di rumah mati total pas cuaca lagi panas-panasnya. Teknisi responsnya cepat sekali, datang tepat waktu dan langsung bisa tahu masalahnya di mana. Kerjaanya juga rapih, spare part yang dibawa sesuai. Penjelasannya jujur dan mudah dimengerti. Sangat direkomendasikan!'],
            (object)['user' => 'Giliang Ferdianah Darmawan', 'city' => 'Bekasi', 'rating' => 5.0, 'review' => 'Pertama kali coba servis lewat aplikasi ini dan puas sekali dengan servis dari teknisinya. Orangnya sopan, penjelasannya detail dari awal pengecekan sampai selesai. Yang saya tanya sopan, kerjanya sangat rapih selain itu orangnya ramah dan penjelasannya mudah dimengerti. Terima kasih!'],
        ];

        $total_ratings = collect($ratings)->sum('rating');
        $count_ratings = collect($ratings)->count();
        $average_rating = $count_ratings > 0 ? number_format($total_ratings / $count_ratings, 1) : '5.0';

        // Kirim data ke view baru
        return view('customer.technician_profile', compact('technician', 'ratings', 'average_rating'));
    }
}
