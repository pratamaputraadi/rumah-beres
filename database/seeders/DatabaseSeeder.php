<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Order;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat ADMIN
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@rumahberes.com',
            'password' => bcrypt('password'), // Password: password
            'role' => 'admin',
            'is_verified' => true
        ]);

        // 2. Buat CUSTOMER (Adi)
        $adi = User::create([
            'name' => 'Adi Pratama Putra',
            'email' => 'adi@email.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'address' => 'Karawang, Jawa Barat',
            'avatar' => '2.png'
        ]);

        // 3. Buat TEKNISI (Pangundian)
        $tech = User::create([
            'name' => 'Pangundian Siagian',
            'email' => 'tech@email.com',
            'password' => bcrypt('password'),
            'role' => 'technician',
            'specialization' => 'Cooling Specialist',
            'is_verified' => true,
            'avatar' => '6.png'
        ]);

        // 4. Buat KATEGORI
        Category::create(['name' => 'Cooling & Air', 'icon' => '1.png']);
        Category::create(['name' => 'Cleaning', 'icon' => '2.png']);
        Category::create(['name' => 'Electronics', 'icon' => '3.png']);

        // 5. Buat ORDER CONTOH
        Order::create([
            'customer_id' => $adi->id,
            'technician_id' => $tech->id,
            'appliance_name' => 'AC Samsung 1PK',
            'description' => 'AC Bocor netes air',
            'status' => 'in_progress',
            'total_price' => 150000
        ]);

        Order::create([
            'customer_id' => $adi->id,
            'technician_id' => null, // Belum diambil teknisi
            'appliance_name' => 'Kulkas LG',
            'description' => 'Tidak dingin',
            'status' => 'pending'
        ]);
    }
}
