<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin
        $admin = User::firstOrCreate([
            'email' => 'admin@inventory.com',
        ], [
            'name' => 'Joni Owner Gudang',
            'password' => Hash::make('password', ['rounds' => 12]),
            'role' => 'Super Admin',
        ]);
        $admin->assignRole('Super Admin');

        // 2. Staff Gudang
        $staff = User::firstOrCreate([
            'email' => 'staff@inventory.com',
        ], [
            'name' => 'Agus Gudang',
            'password' => Hash::make('password', ['rounds' => 12]),
            'role' => 'Staff Gudang',
        ]);
        $staff->assignRole('Staff Gudang');

        // 3. Supplier Entity & Supplier User
        $supplierEntity = Supplier::firstOrCreate([
            'name' => 'PT. Global Technology Indonesia',
        ], [
            'contact_person' => 'Budi Santoso',
            'phone' => '021-5550101',
            'email' => 'contact@globaltech.co.id',
            'address' => 'Jakarta',
        ]);

        $supplier = User::firstOrCreate([
            'email' => 'supplier@inventory.com',
        ], [
            'name' => 'Supplier Global Tech',
            'password' => Hash::make('password', ['rounds' => 12]),
            'role' => 'Supplier',
            'supplier_id' => $supplierEntity->id,
        ]);
        $supplier->assignRole('Supplier');
    }
}
