<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // PROSES VERIFIKASI TEKNISI
    public function verifyTechnician(Request $request, $id)
    {
        $technician = User::findOrFail($id);

        if ($request->action == 'approve') {
            $technician->update(['is_verified' => true]);
            $message = 'Teknisi berhasil disetujui!';
        } else {
            // Jika Reject, hapus file-file
            if ($technician->ktp_photo && Storage::disk('public')->exists($technician->ktp_photo)) {
                Storage::disk('public')->delete($technician->ktp_photo);
            }
            if ($technician->certificate_photo && Storage::disk('public')->exists($technician->certificate_photo)) {
                Storage::disk('public')->delete($technician->certificate_photo);
            }

            $technician->delete();
            $message = 'Teknisi ditolak dan dihapus.';
        }

        return redirect()->route('admin.verification.list')->with('success', $message);
    }

    // TAMPILKAN DETAIL TEKNISI
    public function showTechnician($id)
    {
        $tech = User::findOrFail($id);
        return view('admin.show_technician', compact('tech'));
    }

    // HALAMAN LIST VERIFIKASI
    public function verificationList()
    {
        $pending_techs = User::where('role', 'technician')
            ->where('is_verified', 0)
            ->get();

        return view('admin.verification_list', compact('pending_techs'));
    }

    // --- MANAJEMEN USER ---

    // 1. DAFTAR SEMUA USER (DIPERBAIKI LOGIKA FILTER SPESIALISASI)
    public function usersIndex(Request $request)
    {
        $currentUser = Auth::user();
        $isSuperAdmin = $currentUser->email === 'admin@rumahberes.com';

        // Daftar semua spesialisasi yang tersedia
        $specializations = [
            'Cooling',
            'Entertainment',
            'Laundry',
            'Kitchen',
            'Computing',
            'Water'
        ];

        $query = User::latest();

        // 1. Tentukan default role jika tidak ada parameter 'role' di URL
        $selectedRole = $request->role;

        // Jika tidak ada role yang dipilih, set default:
        if (empty($selectedRole) || $selectedRole == 'all') {
            $selectedRole = $isSuperAdmin ? 'admin' : 'customer';
        }

        // 2. LOGIKA PROTEKSI:
        if (!$isSuperAdmin) {
            $query->where('role', '!=', 'admin');
        }

        // 3. LOGIKA FILTER ROLE
        if ($selectedRole) {
            if (!$isSuperAdmin && $selectedRole == 'admin') {
                abort(403, 'Unauthorized access.');
            }
            $query->where('role', $selectedRole);
        }

        // 4. LOGIKA FILTER SPESIALISASI (BARU)
        $selectedSpecialization = $request->specialization;
        if ($selectedRole == 'technician' && !empty($selectedSpecialization) && in_array($selectedSpecialization, $specializations)) {
            $query->where('specialization', $selectedSpecialization);
        }


        $users = $query->get();

        // Kirim $selectedRole, $specializations, dan $selectedSpecialization ke view
        return view('admin.users.index', compact('users', 'isSuperAdmin', 'selectedRole', 'specializations', 'selectedSpecialization'));
    }

    // TAMBAH ADMIN BARU (REDIRECT KE BACK())
    public function userStore(Request $request)
    {
        if (Auth::user()->email !== 'admin@rumahberes.com') {
            abort(403, 'Hanya Super Admin yang bisa menambah Admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin',
            'is_verified' => 1
        ]);

        return back()->with('success', 'Admin baru berhasil ditambahkan!');
    }

    // 2. LIHAT DETAIL USER
    public function userShow($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    // 3. FORM EDIT USER (Tambahkan kiriman variabel $isSuperAdminUser)
    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        $isSuperAdminUser = $user->email === 'admin@rumahberes.com';

        return view('admin.users.edit', compact('user', 'isSuperAdminUser'));
    }

    // 4. UPDATE DATA USER (USER LAIN, DIPERBAIKI LOGIKA SUPER ADMIN & REDIRECT)
    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isSuperAdminUser = $user->email === 'admin@rumahberes.com';

        if ($isSuperAdminUser) {
            // Jika Super Admin yang di edit, hanya validasi dan update NAMA
            $request->validate(['name' => 'required|string']);

            $user->update([
                'name' => $request->name,
            ]);
        } else {
            // Jika user biasa (Admin, Customer, Teknisi)
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required',
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'address' => $request->address,
                'bio' => $request->bio,
                'is_verified' => $request->is_verified,
            ]);
        }

        // Update Password jika diisi (berlaku untuk semua, termasuk Super Admin)
        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        // PERBAIKAN REDIRECT: Kembali ke halaman filter sebelumnya
        return redirect()->back()->with('success', 'Data user berhasil diperbarui.');
    }

    // 5. HAPUS USER
    public function userDestroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus Data Terkait
        Order::where('customer_id', $user->id)->delete();
        Order::where('technician_id', $user->id)->delete();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        if ($user->ktp_photo && Storage::disk('public')->exists($user->ktp_photo)) {
            Storage::disk('public')->delete($user->ktp_photo);
        }
        if ($user->certificate_photo && Storage::disk('public')->exists($user->certificate_photo)) {
            Storage::disk('public')->delete($user->certificate_photo);
        }

        $user->delete();

        return back()->with('success', 'User dan semua data terkait berhasil dihapus.');
    }

    // =========================================
    // FITUR PROFILE SAYA
    // =========================================

    // 1. Tampilkan Halaman Profile Saya
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    // 2. Proses Update Profile Saya
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'bio'    => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $request->name;
        $user->bio = $request->bio;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
