<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Dior',
            'Chanel',
            'Versace',
            'Paco Rabanne',
            'Carolina Herrera',
            'Jean Paul Gaultier',
            'Dolce & Gabbana',
            'Givenchy',
            'Yves Saint Laurent',
            'Hugo Boss',
            'Calvin Klein',
            'Giorgio Armani',
            'Burberry',
            'Tom Ford',
            'Gucci',
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}