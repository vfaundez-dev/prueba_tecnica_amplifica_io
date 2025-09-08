<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller {

    private array $orders = [];
    private array $products = [];
    
    public function index() {
        $metrics = [];
        $shops = Auth::user()->shops;
        return view('dashboard.index', compact('shops', 'metrics'));
    }

    public function metrics(Request $request) {
        try {

            $shop_id = $request->validate([
                'shop' => 'required|integer|exists:shops,id'
            ]);

            $shops = Auth::user()->shops;
            $shop = $shops->first();

            if (!$shop) {
                return redirect()->route('product.index')->with('error', 'Tienda no encontrada o no autorizada.');
            }

            /* Metricas */
            $this->getDataApi($shop);
            $salesByMonth = $this->getTotalSalesPerMonth();
            $pendingOrders = $this->getPendingOrders();
            $topProducts = $this->getTopProducts();

            $metrics = [
                'shop' => $shop,
                'shop_id' => $shop->id,
                'salesByMonth' => $salesByMonth,
                'salesByCurrentMonth' => $salesByMonth[date('Y-m')] ?? 0,
                'pendingOrders' => $pendingOrders,
                'topProducts' => $topProducts,
                'topProduct' => $topProducts[0] ?? null,
            ];

            return view('dashboard.index', compact('shops', 'metrics', 'shop'));

        } catch (\Throwable $th) {
            Log::error('DashboardController metrics error: ' . $th->getMessage());
            return redirect()->route('dashboard.index')->with('error', 'Ocurrió un error al obtener métricas.');
        }
    }

    private function getDataApi($shop) {
        try {

            $client = $shop->platform === 'shopify' 
                ? new \App\Services\Ecommerce\ShopifyClient( $shop->url, $shop->access_token, '2025-07' )
                : new \App\Services\Ecommerce\WooCommerceClient( $shop->url, $shop->api_key, $shop->api_secret );

            $this->products = $client->getProducts();
            $this->orders = $client->getOrders();

        } catch (\Throwable $th) {
            Log::error('DashboardController getDataApi error: ' . $th->getMessage());
            return [];
        }
    }

    /*--- CALCULOS DE METRICAS ---*/

    private function getTotalSalesPerMonth() {
        $sales = [];

        foreach ($this->orders as $order) {
            $month = date('Y-m', strtotime($order['created_at']));
            $price = $order['total_price'] ?? 0;
            $sales[$month] = ($sales[$month] ?? 0) + $price;
        }

        return $sales;
    }

    private function getPendingOrders(): int {
        return count( array_filter($this->orders, function ($order) {
            return isset($order['payment_status'])
                ? $order['payment_status'] === 'pending'
                : false;
        }));
    }

    private function getTopProducts(): array {
        $products = [];

        foreach ($this->orders as $order) {
            if (!empty($order['items'])) {
                foreach ($order['items'] as $item) {
                    $title = $item['title'];
                    $quantity = $item['quantity'] ?? 0;

                    if (!isset($products[$title])) {
                        $products[$title] = 0;
                    }

                    $products[$title] += $quantity;
                }
            }
        }

        arsort($products);

        $result = [];
        foreach (array_slice($products, 0, 5, true) as $title => $quantity) {
            $result[] = [
                'title' => $title,
                'quantity' => $quantity,
            ];
        }

        return $result;
    }

}
