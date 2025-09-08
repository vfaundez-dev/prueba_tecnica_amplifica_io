<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller {
    
    public function index() {
        $shops = Auth::user()->shops;
        return view('shop.index', compact('shops'));
    }

    public function store(Request $request) {
        try {

            $requestData = $request->validate([
                'platform' => 'required|string|in:shopify,woocommerce',
                'name' => 'required|string|max:255',
                'url' => 'required|url',
                'api_key'  => 'nullable|string|max:255',
                'api_secret'=> 'nullable|string|max:255',
                'access_token'=> 'nullable|string|max:255',
            ]);

            $requestData['user_id'] = Auth::id();
            Shop::create($requestData);
            return redirect()->route('shop.index')->with('success', 'Tienda agregada exitosamente.');

        } catch (\Throwable $th) {
            Log::error('Error al agregar tienda: ' . $th->getMessage());
            return redirect()->route('shop.index')->with('error', 'Ocurrió un error al agregar la tienda.');
        }
    }

}
