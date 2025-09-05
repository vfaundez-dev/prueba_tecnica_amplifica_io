<?php

namespace App\Services;

interface EcommerceClient {
    public function testConnection(): bool;
    public function getProducts(): array;
    public function getOrders(): array;
}
