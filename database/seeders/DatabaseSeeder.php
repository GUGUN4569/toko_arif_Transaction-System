<?php
 
namespace Database\Seeders;
 
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
 
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@tokoarif.com'],
            [
                'name'     => 'Admin Toko Arif',
                'role'     => 'admin',
                'password' => Hash::make('password'),
            ]
        );
 
        // Kasir
        User::firstOrCreate(
            ['email' => 'kasir@tokoarif.com'],
            [
                'name'     => 'Kasir Toko Arif',
                'role'     => 'kasir',
                'password' => Hash::make('password'),
            ]
        );
    }
}