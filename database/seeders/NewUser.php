<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NewUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'cra',
            'email' => 'littleuser@gmail.com',
            'password' => Hash::make('Hola5.2'),
            'rol' => '1',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
