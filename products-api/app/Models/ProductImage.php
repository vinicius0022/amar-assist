<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'path'];

    /**
     * Define the relationship with Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Store the image and return the file path.
     */
    public static function storeImage($file, $i)
    {
        // Generate a unique filename and store the image
        $filename = time() . $i . '.' . $file->extension();
        $filePath = $file->storeAs('public/images', $filename);
        return $filePath;
    }
}
