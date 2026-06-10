<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndTransactionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_access_dashboard(): void
    {
        User::factory()->create([
            'email' => 'kasir@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'kasir@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_transaction_detail_page_shows_transaction_items(): void
    {
        $customer = Customer::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
        ]);

        $category = Category::create([
            'name' => 'Makanan',
            'description' => 'Kategori makanan',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Kopi',
            'price' => 15000,
            'stock' => 10,
        ]);

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'total' => 15000,
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 15000,
            'subtotal' => 15000,
        ]);

        $this->actingAs(User::factory()->create());

        $response = $this->get('/transactions/' . $transaction->id . '/details');

        $response->assertOk();
        $response->assertSee('Detail Transaksi');
        $response->assertSee($product->name);
    }
}
