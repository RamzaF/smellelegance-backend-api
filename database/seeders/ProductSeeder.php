<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Dior
            ['name' => 'Dior Sauvage', 'brand' => 'Dior', 'category' => 'Aromática', 'price' => 129.99, 'description' => 'Una fragancia masculina fresca y especiada con notas de bergamota y pimienta.'],
            ['name' => 'Miss Dior', 'brand' => 'Dior', 'category' => 'Floral', 'price' => 135.00, 'description' => 'Elegante perfume floral con rosa de Grasse y peonía.'],
            
            // Chanel
            ['name' => 'Chanel N°5', 'brand' => 'Chanel', 'category' => 'Floral', 'price' => 150.00, 'description' => 'El icónico perfume floral aldehídico, atemporal y sofisticado.'],
            ['name' => 'Bleu de Chanel', 'brand' => 'Chanel', 'category' => 'Amaderada', 'price' => 142.00, 'description' => 'Fragancia amaderada aromática para hombre, fresca y elegante.'],
            
            // Versace
            ['name' => 'Versace Eros', 'brand' => 'Versace', 'category' => 'Oriental', 'price' => 98.00, 'description' => 'Perfume masculino intenso con menta, vainilla y tonka.'],
            ['name' => 'Bright Crystal', 'brand' => 'Versace', 'category' => 'Floral', 'price' => 89.99, 'description' => 'Fragancia femenina floral y frutal con yuzu y granada.'],
            
            // Paco Rabanne
            ['name' => '1 Million', 'brand' => 'Paco Rabanne', 'category' => 'Oriental', 'price' => 95.00, 'description' => 'Perfume masculino especiado con canela, cuero y ámbar.'],
            ['name' => 'Invictus', 'brand' => 'Paco Rabanne', 'category' => 'Fresca', 'price' => 92.00, 'description' => 'Fragancia deportiva con notas acuáticas y amaderadas.'],
            
            // Carolina Herrera
            ['name' => '212 VIP Men', 'brand' => 'Carolina Herrera', 'category' => 'Aromática', 'price' => 105.00, 'description' => 'Perfume masculino con vodka helado, jengibre y caviar de lima.'],
            ['name' => 'Good Girl', 'brand' => 'Carolina Herrera', 'category' => 'Oriental', 'price' => 125.00, 'description' => 'Fragancia femenina sensual con almendra, café y tuberosa.'],
            ['name' => 'CH Men', 'brand' => 'Carolina Herrera', 'category' => 'Aromática', 'price' => 98.00, 'description' => 'Perfume masculino elegante con notas de cuero y especias.'],
            
            // Jean Paul Gaultier
            ['name' => 'Le Male', 'brand' => 'Jean Paul Gaultier', 'category' => 'Oriental', 'price' => 89.00, 'description' => 'Clásico masculino con lavanda, menta y vainilla.'],
            ['name' => 'Scandal', 'brand' => 'Jean Paul Gaultier', 'category' => 'Gourmand', 'price' => 110.00, 'description' => 'Fragancia femenina provocativa con miel, gardenia y pachulí.'],
            
            // Dolce & Gabbana
            ['name' => 'The One', 'brand' => 'Dolce & Gabbana', 'category' => 'Oriental', 'price' => 115.00, 'description' => 'Perfume masculino sofisticado con tabaco, ámbar y cardamomo.'],
            ['name' => 'Light Blue', 'brand' => 'Dolce & Gabbana', 'category' => 'Cítrica', 'price' => 99.00, 'description' => 'Fragancia fresca mediterránea con limón siciliano y manzana.'],
            
            // Givenchy
            ['name' => 'Gentleman', 'brand' => 'Givenchy', 'category' => 'Aromática', 'price' => 108.00, 'description' => 'Perfume masculino elegante con iris, lavanda y pachulí.'],
            ['name' => 'L\'Interdit', 'brand' => 'Givenchy', 'category' => 'Floral', 'price' => 120.00, 'description' => 'Fragancia femenina audaz con flor de naranja y jazmín.'],
            
            // Yves Saint Laurent
            ['name' => 'Y Eau de Parfum', 'brand' => 'Yves Saint Laurent', 'category' => 'Aromática', 'price' => 112.00, 'description' => 'Perfume masculino intenso con lavanda, salvia y cedro.'],
            ['name' => 'Black Opium', 'brand' => 'Yves Saint Laurent', 'category' => 'Gourmand', 'price' => 130.00, 'description' => 'Fragancia femenina adictiva con café, vainilla y flor de naranja.'],
            ['name' => 'Libre', 'brand' => 'Yves Saint Laurent', 'category' => 'Floral', 'price' => 135.00, 'description' => 'Perfume femenino moderno con lavanda, flor de naranja y almizcle.'],
            
            // Hugo Boss
            ['name' => 'Boss Bottled', 'brand' => 'Hugo Boss', 'category' => 'Amaderada', 'price' => 85.00, 'description' => 'Clásico masculino con manzana, canela y sándalo.'],
            ['name' => 'The Scent', 'brand' => 'Hugo Boss', 'category' => 'Oriental', 'price' => 92.00, 'description' => 'Fragancia sensual con jengibre, cuero exótico y maninka.'],
            
            // Calvin Klein
            ['name' => 'CK One', 'brand' => 'Calvin Klein', 'category' => 'Cítrica', 'price' => 65.00, 'description' => 'Icónica fragancia unisex fresca y limpia.'],
            ['name' => 'Euphoria', 'brand' => 'Calvin Klein', 'category' => 'Oriental', 'price' => 78.00, 'description' => 'Perfume femenino seductor con granada, orquídea negra y ámbar.'],
            
            // Giorgio Armani
            ['name' => 'Acqua di Giò', 'brand' => 'Giorgio Armani', 'category' => 'Fresca', 'price' => 105.00, 'description' => 'Fragancia masculina acuática con notas marinas y cítricas.'],
            ['name' => 'Sì Passione', 'brand' => 'Giorgio Armani', 'category' => 'Floral', 'price' => 125.00, 'description' => 'Perfume femenino apasionado con pera, rosa y vainilla.'],
            
            // Burberry
            ['name' => 'Burberry Her', 'brand' => 'Burberry', 'category' => 'Gourmand', 'price' => 118.00, 'description' => 'Fragancia femenina frutal con fresas, grosella negra y almizcle.'],
            ['name' => 'Mr. Burberry', 'brand' => 'Burberry', 'category' => 'Amaderada', 'price' => 110.00, 'description' => 'Perfume masculino británico con pomelo, abedul y vetiver.'],
            
            // Tom Ford
            ['name' => 'Black Orchid', 'brand' => 'Tom Ford', 'category' => 'Oriental', 'price' => 185.00, 'description' => 'Fragancia lujosa y sensual con trufa negra, ylang-ylang y pachulí.'],
            
            // Gucci
            ['name' => 'Gucci Guilty', 'brand' => 'Gucci', 'category' => 'Oriental', 'price' => 102.00, 'description' => 'Perfume provocativo con mandarina, lila y pachulí.'],
        ];

        foreach ($products as $product) {
            $brandId = DB::table('brands')->where('name', $product['brand'])->value('id');
            $categoryId = DB::table('categories')->where('name', $product['category'])->value('id');

            DB::table('products')->insert([
                'brand_id' => $brandId,
                'category_id' => $categoryId,
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock_available' => rand(5, 50),
                'image' => null, // Se agregarán imágenes después
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}