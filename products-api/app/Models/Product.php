<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Define which fields can be mass-assigned
    protected $fillable = ['title', 'description', 'sale_price', 'cost', 'active'];

    // Validate sale price and cost
    public static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            if ($product->sale_price < ($product->cost * 1.10)) {
                throw new \Exception("O preço do produto não pode ser inferior ao custo do produto + 10%.");
            }
        });
    }

    // For images, we will set up a relationship (this will be handled later)
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
