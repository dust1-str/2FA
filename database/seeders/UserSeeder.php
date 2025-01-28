<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('testing'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
