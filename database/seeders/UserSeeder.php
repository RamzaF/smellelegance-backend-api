<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admin
            [
                'name' => 'Administrador',
                'email' => 'admin@smellelegance.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567890',
                'address' => '123 Admin Street, City',
                'role' => 'admin',
                'is_active' => true,
            ],
            // Clientes
            [
                'name' => 'María García',
                'email' => 'maria.garcia@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567891',
                'address' => '456 Client Ave, City',
                'role' => 'client',
                'is_active' => true,
            ],
            [
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567892',
                'address' => '789 Customer Blvd, City',
                'role' => 'client',
                'is_active' => true,
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana.martinez@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567893',
                'address' => '321 Buyer Lane, City',
                'role' => 'client',
                'is_active' => true,
            ],
            [
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567894',
                'address' => '654 Shopper St, City',
                'role' => 'client',
                'is_active' => true,
            ],
            // Personal de entrega
            [
                'name' => 'Luis Delivery',
                'email' => 'luis.delivery@smellelegance.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567895',
                'address' => '987 Delivery Hub, City',
                'role' => 'delivery_staff',
                'is_active' => true,
            ],
            [
                'name' => 'Pedro Courier',
                'email' => 'pedro.courier@smellelegance.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567896',
                'address' => '147 Shipping Center, City',
                'role' => 'delivery_staff',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'phone' => $user['phone'],
                'address' => $user['address'],
                'role' => $user['role'],
                'is_active' => $user['is_active'],
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}