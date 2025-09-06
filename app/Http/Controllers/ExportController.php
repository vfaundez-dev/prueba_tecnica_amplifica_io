<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller {
    
    public function products(Request $request) {

        $products = session('export_products');
        if (!$products || empty($products)) {
            return redirect()->route('product.index')->with('info', 'No hay productos para exportar.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Productos');

        $sheet->fromArray(
            ['ID', 'Title', 'SKU', 'Description', 'Price', 'Image'],
            null,
            'A1'
        );

        $row = 2;
        foreach ($products as $product) {
            $sheet->fromArray([
                $product['id'] ?? '',
                $product['title'] ?? '',
                $product['sku'] ?? '',
                strip_tags($product['description'] ?? ''),
                $product['price'] ?? '',
            ], null, "A{$row}");
            $row++;
        }

        return $this->downloadExcel($spreadsheet, 'productos.xlsx');

    }

    public function orders(Request $request) {

        $orders = session('export_orders');
        if (!$orders || empty($orders)) {
            return redirect()->route('order.index')->with('info', 'No hay pedidos para exportar.');
        }

        $spreadsheet = new Spreadsheet();

        // --- Hoja 1: Órdenes
        $ordersSheet = $spreadsheet->getActiveSheet();
        $ordersSheet->setTitle('Ordenes');

        // Cabecera
        $ordersSheet->fromArray(
            ['ID', 'Order Number', 'Total Price', 'Currency', 'Created At', 'Payment Status'],
            null,
            'A1'
        );

        $row = 2;
        foreach ($orders as $order) {
            // 👇 Forzar ID y Order Number como texto
            $ordersSheet->setCellValueExplicit("A{$row}", $order['id'] ?? '', DataType::TYPE_STRING);
            $ordersSheet->setCellValueExplicit("B{$row}", $order['order_number'] ?? '', DataType::TYPE_STRING);
            $ordersSheet->setCellValue("C{$row}", $order['total_price'] ?? '');
            $ordersSheet->setCellValue("D{$row}", $order['currency'] ?? '');
            $ordersSheet->setCellValue("E{$row}", $order['created_at'] ?? '');
            $ordersSheet->setCellValue("F{$row}", $order['payment_status'] ?? '');
            $row++;
        }

        // --- Hoja 2: Clientes
        $customerSheet = $spreadsheet->createSheet();
        $customerSheet->setTitle('Clientes');
        $customerSheet->fromArray(
            ['Order ID', 'Customer ID', 'First Name', 'Last Name', 'Email'],
            null,
            'A1'
        );

        $row = 2;
        foreach ($orders as $order) {
            if (!empty($order['customer'])) {
                $customer = $order['customer'];

                $customerSheet->setCellValueExplicit("A{$row}", $order['id'] ?? '', DataType::TYPE_STRING);
                $customerSheet->setCellValueExplicit("B{$row}", $customer['id'] ?? '', DataType::TYPE_STRING);
                $customerSheet->setCellValue("C{$row}", $customer['first_name'] ?? '');
                $customerSheet->setCellValue("D{$row}", $customer['last_name'] ?? '');
                $customerSheet->setCellValue("E{$row}", $customer['email'] ?? '');
                $row++;
            }
        }

        // --- Hoja 3: Productos
        $itemsSheet = $spreadsheet->createSheet();
        $itemsSheet->setTitle('Productos');
        $itemsSheet->fromArray(
            ['Order ID', 'Item ID', 'Title', 'SKU', 'Quantity', 'Price'],
            null,
            'A1'
        );

        $row = 2;
        foreach ($orders as $order) {
            if (!empty($order['items'])) {
                foreach ($order['items'] as $item) {
                    $itemsSheet->setCellValueExplicit("A{$row}", $order['id'] ?? '', DataType::TYPE_STRING);
                    $itemsSheet->setCellValueExplicit("B{$row}", $item['id'] ?? '', DataType::TYPE_STRING);
                    $itemsSheet->setCellValue("C{$row}", $item['title'] ?? '');
                    $itemsSheet->setCellValue("D{$row}", $item['sku'] ?? '');
                    $itemsSheet->setCellValue("E{$row}", $item['quantity'] ?? '');
                    $itemsSheet->setCellValue("F{$row}", $item['price'] ?? '');
                    $row++;
                }
            }
        }

        return $this->downloadExcel($spreadsheet, 'ordenes.xlsx');
    }

    private function downloadExcel(Spreadsheet $spreadsheet, string $filename): StreamedResponse {
        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

}
