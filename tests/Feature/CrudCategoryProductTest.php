<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CrudCategoryProductTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $this->actingAs($user);
    }

    public function test_admin_can_create_category_and_product(): void
    {
        $this->actingAsAdmin();

        $categoryResponse = $this->post('/categories', [
            'name' => 'Minuman',
            'description' => 'Kategori minuman',
        ]);

        $categoryResponse->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Minuman']);

        $category = Category::first();

        $productResponse = $this->post('/products', [
            'category_id' => $category->id,
            'name' => 'Es Teh',
            'description' => 'Es teh manis',
            'price' => 5000,
            'stock' => 20,
        ]);

        $productResponse->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['name' => 'Es Teh']);
        $this->assertTrue(Product::where('name', 'Es Teh')->exists());
    }

    public function test_invalid_category_and_product_data_are_rejected(): void
    {
        $this->actingAsAdmin();

        $categoryResponse = $this->from('/categories/create')->post('/categories', [
            'name' => str_repeat('A', 256),
            'description' => str_repeat('B', 256),
        ]);

        $categoryResponse->assertSessionHasErrors(['name', 'description']);
        $this->assertDatabaseMissing('categories', ['name' => str_repeat('A', 256)]);

        $category = Category::create(['name' => 'Minuman']);

        $productResponse = $this->from('/products/create')->post('/products', [
            'category_id' => $category->id,
            'name' => str_repeat('C', 256),
            'description' => str_repeat('D', 1001),
            'price' => -1,
            'stock' => -5,
        ]);

        $productResponse->assertSessionHasErrors(['name', 'description', 'price', 'stock']);
        $this->assertDatabaseMissing('products', ['name' => str_repeat('C', 256)]);
    }

    public function test_product_form_renders_field_specific_error_containers(): void
    {
        $this->actingAsAdmin();

        $response = $this->from('/products/create')->post('/products', [
            'category_id' => '',
            'name' => '',
            'description' => '',
            'price' => '',
            'stock' => '',
        ]);

        $response->assertSessionHasErrors(['category_id', 'name', 'price', 'stock']);

        $followedResponse = $this->followRedirects($response);

        $followedResponse->assertSee('id="category_id-error"', false);
        $followedResponse->assertSee('id="name-error"', false);
        $followedResponse->assertSee('id="price-error"', false);
        $followedResponse->assertSee('id="stock-error"', false);
    }
}
