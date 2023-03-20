<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\License;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        License::create([
            'enkripsi' => 'u23423u4i32u4i32',
            'domain' => 'localhost:8000',
            'license_key' => '1234567890',
            'expired_at' => '2023-03-19'
        ]);
    }
}
