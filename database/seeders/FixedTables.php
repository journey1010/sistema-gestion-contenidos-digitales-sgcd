<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixedTables extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->update([
            'app_name' => 'ufii'
        ]);

        DB::table('publicaciones')->update([
            'app_name' => 'ufii'
        ]);

        DB::table('national_reports')->update([
            'app_name' => 'ufii'
        ]);

        DB::table('banners')->update([
            'app_name' => 'ufii'
        ]);
    }
}
