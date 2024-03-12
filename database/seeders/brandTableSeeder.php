<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class brandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        DB::table('brands')->insert([
            'name' => 'Robots',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        DB::table('brands')->insert([
            'name' => 'Sumsang',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        DB::table('brands')->insert([
            'name' => 'BlueDragon',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        DB::table('brands')->insert([
            'name' => 'Lenova',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
