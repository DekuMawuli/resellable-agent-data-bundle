<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Services\OrderStatusSyncService;
use App\Services\RealestApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OrderStatusSyncServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_sync_order_updates_a_processing_order_from_the_provider(): void
    {
        $this->mockRealest([
            'status' => 'success',
            'data' => [
                'order_status' => 'completed',
            ],
        ]);

        $order = Order::factory()->create([
            'payment_made' => true,
            'provider_reference' => 'REF-123',
            'provider_status' => 'processing',
            'status' => 'processing',
        ]);

        $result = app(OrderStatusSyncService::class)->syncOrder($order, true);

        $this->assertTrue($result['updated']);
        $this->assertSame('completed', $order->fresh()->status);
        $this->assertSame('completed', $order->fresh()->provider_status);
    }

    public function test_sync_pending_provider_orders_only_processes_orders_old_enough_for_refresh(): void
    {
        $this->mockRealest([
            'status' => 'success',
            'data' => [
                'order_status' => 'completed',
            ],
        ]);

        $eligibleOrder = Order::factory()->create([
            'payment_made' => true,
            'provider_reference' => 'REF-OLD',
            'provider_status' => 'processing',
            'status' => 'processing',
            'updated_at' => now()->subMinutes(10),
        ]);

        $recentOrder = Order::factory()->create([
            'payment_made' => true,
            'provider_reference' => 'REF-NEW',
            'provider_status' => 'processing',
            'status' => 'processing',
            'updated_at' => now(),
        ]);

        $result = app(OrderStatusSyncService::class)->syncPendingProviderOrders(limit: 10, minAgeMinutes: 2);

        $this->assertSame(1, $result['synced']);
        $this->assertSame(1, $result['updated']);
        $this->assertSame('completed', $eligibleOrder->fresh()->status);
        $this->assertSame('processing', $recentOrder->fresh()->status);
    }

    private function mockRealest(array $response): void
    {
        $mock = Mockery::mock(RealestApiService::class);
        $mock->shouldReceive('isReady')->andReturn(true);
        $mock->shouldReceive('getOrderStatus')->andReturn($response);

        $this->app->instance(RealestApiService::class, $mock);
    }
}
