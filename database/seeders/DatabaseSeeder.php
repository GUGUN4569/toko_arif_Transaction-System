<?php
 
namespace Database\Seeders;
 
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
 
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun admin default jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@tokoarif.com'],
            [
                'name'     => 'Admin Toko Arif',
                'password' => Hash::make('password'),
            ]
        );
    }
}