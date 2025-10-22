<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Floral',
                'description' => 'Fragancias con notas florales predominantes, elegantes y femeninas.',
            ],
            [
                'name' => 'Oriental',
                'description' => 'Perfumes cálidos y sensuales con notas especiadas y ambarinas.',
            ],
            [
                'name' => 'Amaderada',
                'description' => 'Aromas con base de maderas como cedro, sándalo y vetiver.',
            ],
            [
                'name' => 'Cítrica',
                'description' => 'Fragancias frescas y energizantes con notas de limón, naranja y bergamota.',
            ],
            [
                'name' => 'Fresca',
                'description' => 'Perfumes ligeros y refrescantes, ideales para el día.',
            ],
            [
                'name' => 'Aromática',
                'description' => 'Combinaciones herbales y aromáticas con lavanda, salvia y romero.',
            ],
            [
                'name' => 'Gourmand',
                'description' => 'Notas dulces y comestibles como vainilla, caramelo y chocolate.',
            ],
            [
                'name' => 'Especiada',
                'description' => 'Fragancias intensas con canela, pimienta y clavo de olor.',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}