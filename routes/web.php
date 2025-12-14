<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController; // Pastikan OrderController diimpor

// 1. Halaman Depan (Landing Page)
Route::get('/', function () {
    return view('landing');
})->name('home');

// 2. Jalur Login & Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Pendaftaran / Register ---

// 1. Pilihan Daftar (Customer / Teknisi)
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterChoice'])->name('register.choice');

// 2. Daftar Customer
Route::get('/register/customer', [App\Http\Controllers\AuthController::class, 'showRegisterCustomer'])->name('register.customer');
Route::post('/register/customer', [App\Http\Controllers\AuthController::class, 'storeCustomer'])->name('register.customer.store');

// 3. Daftar Teknisi
Route::get('/register/technician', [App\Http\Controllers\AuthController::class, 'showRegisterTechnician'])->name('register.technician');
Route::post('/register/technician', [App\Http\Controllers\AuthController::class, 'storeTechnician'])->name('register.technician.store');

// 3. Jalur Dashboard & Fitur Dalam (Hanya bisa dibuka kalau sudah Login)
Route::middleware(['auth'])->group(function () {

    // Dashboard Utama (Incoming Job Requests)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Baru untuk Accepted Jobs (Menu Request)
    Route::get('/technician/accepted-jobs', [DashboardController::class, 'acceptedJobs'])->name('technician.accepted_jobs');

    // --- FITUR PROFILE TECHNICIAN ---
    Route::get('/technician/profile', [App\Http\Controllers\DashboardController::class, 'technicianProfile'])->name('technician.profile');
    Route::put('/technician/profile', [App\Http\Controllers\DashboardController::class, 'updateTechnicianProfile'])->name('technician.profile.update');


    // --- FITUR BOOKING (ORDER) & QUOTE ---
    // 1. Halaman Formulir Booking (Menerima ID Teknisi Opsional)
    Route::get('/booking/create/{technician_id?}', [OrderController::class, 'create'])->name('booking.create');

    // 2. Proses Simpan Booking ke Database
    Route::post('/booking/store', [OrderController::class, 'store'])->name('booking.store');

    // 3. Update Status (Untuk Teknisi)
    Route::post('/booking/{id}/update', [OrderController::class, 'updateStatus'])->name('booking.update');

    // 4. Halaman Detail Order (Timeline & Map)
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');

    // 5. TEKNISI: Menyimpan Quote (Items) dan Update Status ke 'quote_sent'
    Route::post('/order/{id}/save-quote', [OrderController::class, 'saveQuote'])->name('order.save_quote');

    // 6. CUSTOMER: Mengunduh Invoice (TXT)
    Route::get('/order/{id}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('order.download_invoice');

    // ========================================================
    // AKSI CUSTOMER BARU (NON-AJAX / FORM SUBMISSION)
    // ========================================================
    // 7. CUSTOMER: Menyetujui Quote (Mengubah status ke 'quote_approved')
    Route::post('/order/{id}/approve', [OrderController::class, 'approveQuote'])->name('order.approve');

    // 8. CUSTOMER: Menandai sebagai Sudah Dibayar (Mengubah status ke 'paid')
    Route::post('/order/{id}/paid', [OrderController::class, 'markAsPaid'])->name('order.mark_paid');

    // Rute API lama (Dibiarkan di sini agar Controller tidak error jika masih ada yang memanggil)
    Route::post('/api/order/{id}/approve-quote', [OrderController::class, 'approveQuote'])->name('api.order.approve_quote');
    Route::post('/api/order/{id}/mark-paid', [OrderController::class, 'markAsPaid'])->name('api.order.mark_paid');
    // ========================================================


    // --- FITUR CHAT BARU ---
    // Mengabaikan Chat sesuai permintaan (rute tetap ada, tapi fitur dinonaktifkan di Blade)
    Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/history/{orderId}', [App\Http\Controllers\ChatController::class, 'getHistory'])->name('chat.history');


    // --- FITUR ADMIN ---
    // 1. Proses Verifikasi (Tombol Approve/Reject)
    Route::post('/admin/verify/{id}', [App\Http\Controllers\AdminController::class, 'verifyTechnician'])->name('admin.verify');

    // 2. Halaman Detail Teknisi (Review KTP)
    Route::get('/admin/technician/{id}', [App\Http\Controllers\AdminController::class, 'showTechnician'])->name('admin.show_tech');

    // 3. Halaman Menu Verifikasi Teknisi (List Antrian)
    Route::get('/admin/verification-list', [App\Http\Controllers\AdminController::class, 'verificationList'])->name('admin.verification.list');


    // --- FITUR CUSTOMER ---
    // 1. Profile
    Route::get('/profile', [App\Http\Controllers\CustomerController::class, 'profile'])->name('customer.profile');
    Route::put('/profile', [App\Http\Controllers\CustomerController::class, 'updateProfile'])->name('customer.profile.update');

    // 2. Orders & Categories
    Route::get('/orders', [App\Http\Controllers\CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/categories', [App\Http\Controllers\CustomerController::class, 'categories'])->name('customer.categories');

    // 3. Detail Kategori (List Teknisi)
    Route::get('/categories/{slug}', [App\Http\Controllers\CustomerController::class, 'showCategory'])->name('customer.category.show');

    // 4. Detail Profile Teknisi (Public View)
    Route::get('/categories/{slug}/{id}', [App\Http\Controllers\CustomerController::class, 'showTechnicianProfile'])->name('customer.technician.show');


    // --- MANAJEMEN USER (ADMIN) ---
    // 1. Daftar Semua User
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'usersIndex'])->name('admin.users.index');

    // 2. Lihat Detail User
    Route::get('/admin/users/{id}/show', [App\Http\Controllers\AdminController::class, 'userShow'])->name('admin.users.show');

    // 3. Edit User (Form)
    Route::get('/admin/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'userEdit'])->name('admin.users.edit');

    // 4. Update User (Proses Simpan)
    Route::put('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'userUpdate'])->name('admin.users.update');

    // Route Tambah Admin Baru (Super Admin Only)
    Route::post('/admin/users/store', [App\Http\Controllers\AdminController::class, 'userStore'])->name('admin.users.store');

    // 5. Hapus User
    Route::delete('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'userDestroy'])->name('admin.users.destroy');

    // --- FITUR PROFILE ADMIN ---
    // Halaman Profile Saya (Untuk Admin mengedit diri sendiri)
    Route::get('/admin/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('admin.profile');
    Route::put('/admin/profile', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('admin.profile.update');
});
