<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller {
    
    public function index() {
        $shops = Auth::user()->shops;
        return view('order.index', compact('shops'));
    }

    public function getAll(Request $request) {
        try {

            $shop_id = $request->validate([
                'shop' => 'required|integer|exists:shops,id'
            ]);
            
            $shop = Shop::where('id', $shop_id)
                ->where('user_id', Auth::id())
                ->first();
            
            if (!$shop) {
                return redirect()->route('order.index')->with('error', 'Tienda no encontrada o no autorizada.');
            }

            $shops = Shop::where( 'user_id', Auth::id() )->get();
            $orders = $this->getDataApi($shop);
            session(['export_orders' => $orders]);

            return view('order.index', compact('shops', 'orders', 'shop_id'));
                
        } catch (\Throwable $th) {
            Log::error('OrderController getAll error: ' . $th->getMessage());
            return redirect()->route('order.index')->with('error', 'Ocurrió un error al obtener los pedidos.');
        }
    }

    /*--- METODOS PARA LAS API EXTERNAS (SHOPIFY, WOOCOMMERCE, ETC) ---*/

    private function getDataApi($shop) {
        try {

            $client = $shop->platform === 'shopify' 
                ? new \App\Services\Ecommerce\ShopifyClient( $shop->url, $shop->access_token, '2025-07' )
                : new \App\Services\Ecommerce\WooCommerceClient( $shop->url, $shop->api_key, $shop->api_secret );

            return $client->getOrders();

        } catch (\Throwable $th) {
            Log::error('OrderController getDataApi error: ' . $th->getMessage());
            return [];
        }
    }
}
