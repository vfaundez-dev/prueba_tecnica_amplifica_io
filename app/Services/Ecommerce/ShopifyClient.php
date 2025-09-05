<?php

namespace App\Services\Ecommerce;

use App\Services\EcommerceClient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ShopifyClient implements EcommerceClient {
    
    protected Client $http;
    protected string $domain;
    protected string $token;
    
    public function __construct(string $shop_url, string $token, string $api_version) {
        $this->domain = $shop_url;
        $this->token = $token;
        $this->http = new Client([
            'base_uri' => "{$shop_url}/admin/api/{$api_version}/",
        ]);
    }

    public function testConnection(): bool {
        try {

            $response = $this->http->get('shop.json', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'X-Shopify-Access-Token' => $this->token
                ],
            ]);

            return $response->getStatusCode() === 200;

        } catch (\Exception $e) {
            Log::error('ShopifyClient testConnection error: ' . $e->getMessage());
            return false;
        }
    }

    public function getProducts(): array {
        try {

            $products = [];
            $response = $this->http->get('products.json', [
                'headers' => ['X-Shopify-Access-Token' => $this->token],
                'query'   => ['limit' => 50],
            ]);

            $data = json_decode($response->getBody(), true);

            if ( isset($data['products']) ) {
                foreach ($data['products'] as $key => $product) {
                    $products[] = [
                        'id' => $product['id'],
                        'title' => $product['title'],
                        'description' => $product['body_html'],
                        'sku' => $product['variants'][0]['sku'] ?? null,
                        'price' => $product['variants'][0]['price'] ?? 0,
                        'currency' => $product['variants'][0]['currency'] ?? 'CLP',
                        'image' => $product['image']['src'] ?? null,
                    ];
                }
            }

            return $products;

        } catch (\Exception $e) {
            Log::error('ShopifyClient getProducts error: ' . $e->getMessage());
            return [];
        }
    }

    public function getOrders(): array {
        try {

            $orders = [];
            $response = $this->http->get('orders.json', [
                'headers' => ['X-Shopify-Access-Token' => $this->token],
                'query'   => [
                    'limit' => 50,
                    'status' => 'any',
                    'created_at_min' => now()->subDays(30)->toIso8601ZuluString()
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ( isset($data['orders']) ) {
                foreach ($data['orders'] as $order) {

                    $items = [];
                    foreach ($order['line_items'] as $item) {
                        $items[] = [
                            'id' => $item['id'],
                            'sku' => $item['sku'],
                            'product_id' => $item['product_id'],
                            'variant_id' => $item['variant_id'],
                            'title' => $item['title'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                        ];
                    }

                    $orders[] = [
                        'id' => $order['id'],
                        'order_number' => $order['order_number'],
                        'total_price' => $order['total_price'],
                        'currency' => $order['currency'],
                        'created_at' => $order['created_at'],
                        'payment_status' => $order['financial_status'],
                        'customer' => isset($order['customer'])
                            ? [
                                'id' => $order['customer']['id'],
                                'first_name' => $order['customer']['first_name'],
                                'last_name' => $order['customer']['last_name'],
                                'email' => $order['customer']['email'],
                            ] : null,
                        'items' => $items
                    ];
                }
            }

            return $orders;

        } catch (\Exception $e) {
            Log::error('ShopifyClient getOrders error: ' . $e->getMessage());
            return [];
        }
    }

}
