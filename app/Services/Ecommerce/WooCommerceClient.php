<?php

namespace App\Services\Ecommerce;
use Illuminate\Support\Facades\Log;
use Automattic\WooCommerce\Client as WooClient;

class WooCommerceClient {
    
    protected WooClient $client;

    public function __construct(string $shop_url, string $api_key, string $api_secret) {
        $this->client = new WooClient($shop_url, $api_key, $api_secret, [
            'version' => 'wc/v3',
            'wp_api' => true,
            'query_string_auth' => true,
            'timeout' => 120,
            'verify_ssl' => false,
        ]);
    }

    public function testConnection(): bool {
        try {

            $this->client->get('');
            return true;

        } catch (\Exception $e) {
            Log::error('WooCommerceClient testConnection error: ' . $e->getMessage());
            return false;
        }
    }

    public function getProducts(): array {
        try {

            $products = [];
            $response = $this->client->get('products', ['per_page' => 50]);

            foreach ($response as $key => $product) {
                $products[] = [
                    'id' => $product->id,
                    'title' => $product->name,
                    'description' => $product->description,
                    'sku' => $product->sku ?? null,
                    'price' => $product->price ?? 0,
                    'image' => $product->images[0]->src ?? null,
                ];
            }

            return $products;

        } catch (\Exception $e) {
            Log::error('WooCommerceClient getProducts error: ' . $e->getMessage());
            return [];
        }
    }

    public function getOrders(): array {
        try {

            $orders = [];
            $response = $this->client->get('orders', [
                'per_page' => 50,
                'after' => now()->subDays(30)->toIso8601ZuluString()
            ]);

            Log::info('WooCommerceClient getOrders response: ' . json_encode($response));

            foreach ($response as $order) {

                $items = [];
                foreach ( $order->line_items as $item) {
                    $items[] = [
                        'id' => $item->id,
                        'sku' => $item->sku ?? null,
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variation_id ?? null,
                        'title' => $item->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ];
                }

                $orders[] = [
                    'id' => $order->id,
                    'order_number' => $order->number,
                    'total_price' => $order->total,
                    'currency' => $order->currency,
                    'created_at' => $order->date_created,
                    'customer' => isset($order->billing)
                        ? [
                            'first_name' => $order->billing->first_name,
                            'last_name'  => $order->billing->last_name,
                            'email'      => $order->billing->email,
                        ] : null,
                    'items' => $items,
                ];

            }

            return $orders;

        } catch (\Exception $e) {
            Log::error('WooCommerceClient getOrders error: ' . $e->getMessage());
            return [];
        }
    }

}
