<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::with('images')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        // Store product
        $product = Product::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'sale_price' => $validated['sale_price'],
            'cost' => $validated['cost'],
            'active' => 1,
        ]);

        // Handle images if available
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $imagePath = ProductImage::storeImage($image, $i);

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imagePath,
                ]);
            }
        }

        return response()->json($product->load('images'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::with('images')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, $id)
    {

        // Find product
        $product = Product::findOrFail($id);

        // Validate input
        $validated = $request->validated();

        // Update product
        $product->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'sale_price' => $validated['sale_price'],
            'cost' => $validated['cost'],
            'active' => $validated['active'],
        ]);

        // 🧽 Handle delete images
        if ($request->has('deleted_images')) {
            foreach ($request->input('deleted_images') as $imgId) {
                $image = ProductImage::find($imgId);
                if ($image && $image->product_id == $product->id) {
                    Storage::delete('public/' . $image->path);
                    $image->delete();
                }
            }
        }

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $imagePath = ProductImage::storeImage($image, $i);

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imagePath,
                ]);
            }
        }

        return response()->json($product->load('images'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $product->delete();

        return response()->json(['message' => 'Produto removido']);
    }
}
