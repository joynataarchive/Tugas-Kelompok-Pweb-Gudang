<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * NOTE: RoleSeeder & UserSeeder punya Role 1 masih kosong.
     * Begitu Rava selesai isi filenya, tambahin ke $this->call([...])
     * di bawah ini (taruh SEBELUM ProductSeeder kalau UserSeeder butuh data role).
     */
    public function run(): void
    {
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
