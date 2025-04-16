<?php

namespace Tests\Unit;

use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StoreProductRequestTest extends TestCase
{
    /**
     * Test that the title type as only string.
     *
     * @return void
     */
    public function test_title_type()
    {
        $data = [
            'title' => 1,  // Invalid: type is not string
            'description' => 'Test',
            'sale_price' => 200,
            'cost' => 100,
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    /**
     * Test that title field validation works.
     *
     * @return void
     */
    public function test_title_is_required()
    {
        $data = [
            'title' => '',  // Invalid: empty title
            'description' => 'aaaa',
            'sale_price' => 200,
            'cost' => 100,
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    /**
     * Test title field limit length.
     *
     * @return void
     */
    public function test_title_limit_length()
    {
        $data = [
            'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
            Sed est ante, vestibulum non mi at, pretium malesuada justo. 
            Aliquam erat volutpat. Maecenas feugiat nisl sagittis augue ultricies mattis. 
            Maecenas placerat quam quam, tempor aliquet felis tincidunt. ',  // Invalid: 296 length
            'description' => 'aaaa',
            'sale_price' => 200,
            'cost' => 100,
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('title', $validator->errors()->toArray());
    }

    /**
     * Test that the sale_price type as only numeric.
     *
     * @return void
     */
    public function test_sale_price_type()
    {
        $data = [
            'title' => 'Element',
            'description' => 'Test',
            'sale_price' => true,    // Invalid: type is not numeric
            'cost' => 100,
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('sale_price', $validator->errors()->toArray());
    }

    /**
     * Test that sale_price field validation works.
     *
     * @return void
     */
    public function test_sale_price_is_required()
    {
        $data = [
            'title' => 'Element',
            'description' => 'aaaa',
            'sale_price' => '',  // Invalid: empty sale_price
            'cost' => 100,
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('sale_price', $validator->errors()->toArray());
    }

    /**
     * Test sale_price field limit length.
     *
     * @return void
     */
    public function test_sale_price_min()
    {
        $data = [
            'title' => 'Element',
            'description' => 'aaaa',
            'sale_price' => -1, // Invalid: min = 0
            'cost' => 100,
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('sale_price', $validator->errors()->toArray());
    }

    /**
     * Test that the cost type as only numeric.
     *
     * @return void
     */
    public function test_cost_type()
    {
        $data = [
            'title' => 'Element',
            'description' => 'Test',
            'sale_price' => 100,
            'cost' => true,  // Invalid: type is not numeric
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('cost', $validator->errors()->toArray());
    }

    /**
     * Test that cost field validation works.
     *
     * @return void
     */
    public function test_cost_is_required()
    {
        $data = [
            'title' => 'Element',
            'description' => 'aaaa',
            'sale_price' => 100,
            'cost' => '',   // Invalid: empty cost
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('cost', $validator->errors()->toArray());
    }

    /**
     * Test cost field limit length.
     *
     * @return void
     */
    public function test_cost_min()
    {
        $data = [
            'title' => 'Element',
            'description' => 'aaaa',
            'sale_price' => 100,
            'cost' => -1,  // Invalid: min = 0
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('cost', $validator->errors()->toArray());
    }

    /**
     * Test that description field validation works.
     *
     * @return void
     */
    public function test_description_is_required()
    {
        // Simulate the data for the request (without description)
        $data = [
            'title' => 'Test Product',
            'description' => '',  // Invalid: empty description
            'sale_price' => 200,
            'cost' => 100,
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('description', $validator->errors()->toArray());
    }

    /**
     * Test that the description field can only container allowed html tags.
     *
     * @return void
     */
    public function test_description_html_forbiden_tags()
    {
        // Simulate the data for the request (too short description)
        $data = [
            'title' => 'Test Product',
            'description' => '<p>, <br>, <b> e <strong> <a><br><div>',  // Invalid: forbidden tags
            'sale_price' => 200,
            'cost' => 100,
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('description', $validator->errors()->toArray());
    }

    /**
     * Test that the description type as only string.
     *
     * @return void
     */
    public function test_description_type()
    {
        // Simulate the data for the request (too short description)
        $data = [
            'title' => 'Test Product',
            'description' => [],  // Invalid: type is not string
            'sale_price' => 200,
            'cost' => 100,
        ];

        // Create a validator instance using the StoreProductRequest rules
        $validator = Validator::make($data, (new StoreProductRequest())->rules());

        // Assert that the description field has a validation error
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('description', $validator->errors()->toArray());
    }

    /**
     * Test image field allow only image type (jpeg, png, bmp, gif, svg, or webp).
     */
    public function test_only_image_type()
    {
        $data = [
            'title' => 'Test Product',
            'description' => 'This is a valid description.',
            'sale_price' => 200,
            'cost' => 100,
            'images' => [
                UploadedFile::fake()->create('invalid.pdf', 100, 'application/pdf'),
            ],
        ];

        $request = new StoreProductRequest();

        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('images.0', $validator->errors()->toArray());
    }

    /**
     * Test image larger than 2mb.
     */
    public function test_image_larger_than_2mb()
    {
        $data = [
            'title' => 'Test Product',
            'description' => 'This is a valid description.',
            'sale_price' => 200,
            'cost' => 100,
            'images' => [
                UploadedFile::fake()->create('large.jpg', 3000, 'image/jpeg'),
            ],
        ];

        $request = new StoreProductRequest();

        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('images.0', $validator->errors()->toArray());
    }

    /**
     * Test that image validation accepts jpg or png.
     */
    public function test_accepts_jpg_image()
    {
        $data = [
            'title' => 'Test Product',
            'description' => 'This is a valid description.',
            'sale_price' => 200,
            'cost' => 100,
            'images' => [
                UploadedFile::fake()->image('test.jpg'),
            ],
        ];

        $request = new StoreProductRequest();

        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->fails());
    }

    /**
     * Test that image validation accepts jpg or png.
     */
    public function test_accepts_png_image()
    {
        $data = [
            'title' => 'Test Product',
            'description' => 'This is a valid description.',
            'sale_price' => 200,
            'cost' => 100,
            'images' => [
                UploadedFile::fake()->image('test.png'),
            ],
        ];

        $request = new StoreProductRequest();

        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->fails());
    }

    /**
     * Test that image validation accepts jpg or png.
     */
    public function test_reject_forbidden_image()
    {
        $data = [
            'title' => 'Test Product',
            'description' => 'This is a valid description.',
            'sale_price' => 200,
            'cost' => 100,
            'images' => [
                UploadedFile::fake()->create('test.gif', 100, 'image/gif'),
            ],
        ];

        $request = new StoreProductRequest();

        $rules = $request->rules();

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('images.0', $validator->errors()->toArray());
    }
}
