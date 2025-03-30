<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::create([
            'name' => 'Admin',
            'email' => 'adminpolicy@yopmail.com',
            'password' => Hash::make('Admin@123'), // Securely hash the password
        ]);

        // Assign role (assuming role_id 1 is 'Admin' or any role you want)
        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => 1, // Change this based on your role IDs
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
