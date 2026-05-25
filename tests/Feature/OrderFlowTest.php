<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_place_order_and_payment_simulation_updates_status()
    {
        $this->withoutMiddleware();

        // create user
        $user = User::factory()->create();

        // create products
        $p1 = Product::create([
            'name' => 'Test Product 1',
            'description' => 'Desc',
            'price' => 1000,
            'old_price' => null,
            'stock' => 10,
            'warranty' => null,
            'image' => null,
            'is_active' => true,
            'is_builder' => false,
        ]);

        $p2 = Product::create([
            'name' => 'Test Product 2',
            'description' => 'Desc',
            'price' => 2000,
            'old_price' => null,
            'stock' => 5,
            'warranty' => null,
            'image' => null,
            'is_active' => true,
            'is_builder' => false,
        ]);

        $this->actingAs($user);

        $orderPayload = [
            'items' => [
                ['product_id' => $p1->id, 'quantity' => 2],
                ['product_id' => $p2->id, 'quantity' => 1],
            ],
            'shipping_address' => '123 Test St',
            'phone' => '0123456789',
            'payment_method' => 'simulated',
        ];

        $resp = $this->postJson('/api/orders', $orderPayload);
        $resp->assertStatus(201);

        $resp->assertJson(
            fn(AssertableJson $json) =>
            $json->has('data')
                ->where('data.status', 'pending')
                ->etc()
        );

        $orderId = $resp->json('data.id');

        // assert stock decremented
        $this->assertDatabaseHas('products', ['id' => $p1->id, 'stock' => 8]);
        $this->assertDatabaseHas('products', ['id' => $p2->id, 'stock' => 4]);

        // simulate payment success
        $payResp = $this->postJson('/api/payments/simulate', ['order_id' => $orderId, 'status' => 'paid']);
        $payResp->assertStatus(200);
        $payResp->assertJson(
            fn(AssertableJson $json) =>
            $json->has('data')
                ->where('data.status', 'paid')
                ->etc()
        );

        // simulate payment failure and ensure stock restored
        // place another order to test failure restore
        $orderPayload2 = [
            'items' => [
                ['product_id' => $p1->id, 'quantity' => 1]
            ],
            'shipping_address' => 'Addr',
            'payment_method' => 'simulated',
        ];

        $resp2 = $this->postJson('/api/orders', $orderPayload2);
        $resp2->assertStatus(201);
        $order2Id = $resp2->json('data.id');

        // stock should be decremented
        $this->assertDatabaseHas('products', ['id' => $p1->id, 'stock' => 7]);

        $failResp = $this->postJson('/api/payments/simulate', ['order_id' => $order2Id, 'status' => 'failed']);
        $failResp->assertStatus(200);
        $failResp->assertJson(
            fn(AssertableJson $json) =>
            $json->has('data')
                ->where('data.status', 'failed')
                ->etc()
        );

        // stock restored for p1
        $this->assertDatabaseHas('products', ['id' => $p1->id, 'stock' => 8]);
    }
}
