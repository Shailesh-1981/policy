<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = [
            ['id' => 1, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Agent', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'User', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Insert roles into the database
        DB::table('table_roles')->insert($roles);
    }
}
