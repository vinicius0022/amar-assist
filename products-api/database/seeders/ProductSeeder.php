<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_images')->truncate();
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $product = Product::create([
            'title' => 'MacBook Pro 16"',
            'description' => 'O MacBook Pro de 16" é um notebook da Apple com design elegante e desempenho incrível para profissionais.',
            'sale_price' => 9999.99,
            'cost' => 7000.00,
            'active' => 1,
        ]);

        $this->storeProductImages($product);
    }

    private function storeProductImages(Product $product)
    {
        $images = [
            'macbook_pro_1.jpeg',
            'macbook_pro_2.jpeg',
            'macbook_pro_3.jpg',
        ];

        foreach ($images as $image) {
            $imagePath = "public/images/{$image}";

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $imagePath,
            ]);
        }
    }
}
