<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        DB::table('category')->insert([
            'name' => 'Elektronik',
        ]);

        DB::table('category')->insert([
            'name' => 'Aksesoris'
        ]);

        DB::table('category')->insert([
            'name' => 'PC'
        ]);

        DB::table('category')->insert([
            'name' => 'Mobile'
        ]);

        DB::table('category')->insert([
            'name' => 'Gaming'
        ]);
    }
}
