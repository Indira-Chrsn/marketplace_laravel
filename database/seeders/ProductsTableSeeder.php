<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        $now = now();
        DB::table('products')->insert([
            'name' => 'produk A',
            'price' => 100,
            'stock' => 50,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('products')->insert([
            'name' => 'produk B',
            'price' => 150,
            'stock' => 30,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}