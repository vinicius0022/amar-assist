<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;
use Illuminate\Console\OutputStyle;

class ProductControllerTest extends TestCase
{
    public function test_store_product_with_images()
    {
        // Mock the console output to avoid real prompts in the test
        $mockOutput = Mockery::mock(OutputStyle::class);

        // Mock askQuestion method to simulate user input
        $mockOutput->shouldReceive('askQuestion')
            ->once() // Expect it to be called once
            ->andReturn('mocked_answer'); // Return the mocked user input

        // Simulate product data
        $productData = [
            'title' => 'Test Product',
            'description' => 'Test Description',
            'sale_price' => 200,
            'cost' => 100,
            'active' => 1
        ];

        // Simulate image file upload (you don't need an actual file for testing)
        $fakeImage = UploadedFile::fake()->image('test.jpg', 600, 400); // Fake image

        // Make a POST request to store the product with the fake image
        $response = $this->json('POST', '/api/products', array_merge($productData, [
            'images' => [$fakeImage] // Simulating multiple images
        ]));

        // Assertions to verify the response is correct
        $response->assertStatus(201); // Check for 201 Created status
        $response->assertJsonStructure([
            'id',
            'title',
            'description',
            'sale_price',
            'cost',
            'images'
        ]);

        // Check that the image was handled correctly (this depends on how you are storing it)
        $this->assertDatabaseHas('products', [
            'title' => 'Test Product',
            'cost' => 100
        ]);

        // Mock storage to verify image was stored
        Storage::shouldReceive('put')->once()->andReturn(true);

        // Optionally, check that the product was stored correctly in the database
        $this->assertDatabaseHas('products', ['title' => 'Test Product']);
    }

    public function test_store_product_without_images()
    {
        // Mock the console output to avoid real prompts in the test
        $mockOutput = Mockery::mock(OutputStyle::class);

        // Mock askQuestion method to simulate user input
        $mockOutput->shouldReceive('askQuestion')
            ->once() // Expect it to be called once
            ->andReturn('mocked_answer'); // Return the mocked user input

        // Simulate product data
        $productData = [
            'title' => 'Test Product',
            'description' => 'Test Description',
            'sale_price' => 200,
            'cost' => 100,
            'active' => 1
        ];

        // Make a POST request to store the product with the fake image
        $response = $this->json('POST', '/api/products', $productData);

        // Assertions to verify the response is correct
        $response->assertStatus(201); // Check for 201 Created status
        $response->assertJsonStructure([
            'id',
            'title',
            'description',
            'sale_price',
            'cost',
        ]);

        // Optionally, check that the product was stored correctly in the database
        $this->assertDatabaseHas('products', ['title' => 'Test Product']);
    }
}
