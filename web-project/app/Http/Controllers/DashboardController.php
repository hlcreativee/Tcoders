<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $data = DB::table('transaksi')
            ->selectRaw("
                date,
                Description as product,
                SUM(Quantity) as qty,
                SUM(Quantity * Price) as total
            ")
            ->from(DB::raw("(
                SELECT
                    DATE_FORMAT(STR_TO_DATE(InvoiceDate, '%d/%m/%Y %H:%i'), '%Y-%m') as date,
                    Quantity,
                    Price,
                    Description
                FROM transaksi
                WHERE InvoiceDate IS NOT NULL
            ) as t"))
            ->groupBy('date', 'Description')
            ->orderBy('date')
            ->get();

        $lastData = $data->take(-6)->values();
        $prediksi = 0;

        if ($lastData->count() >= 6) {

            $last = $lastData->last();

            $payload = [
                "t" => count($data),
                "month" => date('m', strtotime($last->date . "-01")),
                "year" => date('Y', strtotime($last->date . "-01")),

                "lag1" => $lastData[5]->qty,
                "lag2" => $lastData[4]->qty,
                "lag3" => $lastData[3]->qty,
                "lag4" => $lastData[2]->qty,
                "lag5" => $lastData[1]->qty,
            ];

            try {
                $response = Http::post('http://127.0.0.1:5000/predict', $payload);
                $prediksi = $response->json()['prediction'] ?? 0;
            } catch (\Exception $e) {
                $prediksi = 0;
            }
        }

        $prediksiProduk = [];

        $produkGroup = $data->groupBy('product');

        foreach ($produkGroup as $namaProduk => $items) {

            $lastItems = $items->take(-6)->values();

            if ($lastItems->count() >= 6) {

                $last = $lastItems->last();

                $payload = [
                    "t" => count($items),
                    "month" => date('m', strtotime($last->date . "-01")),
                    "year" => date('Y', strtotime($last->date . "-01")),

                    "lag1" => $lastItems[5]->qty,
                    "lag2" => $lastItems[4]->qty,
                    "lag3" => $lastItems[3]->qty,
                    "lag4" => $lastItems[2]->qty,
                    "lag5" => $lastItems[1]->qty,
                ];

                try {
                    $response = Http::post('http://127.0.0.1:5000/predict', $payload);

                    $prediksiProduk[$namaProduk] =
                        $response->json()['prediction'] ?? 0;

                } catch (\Exception $e) {
                    $prediksiProduk[$namaProduk] = 0;
                }
            }
        }

        return view('dashboard', [
            'data' => $data,
            'prediksi' => $prediksi,
            'prediksiProduk' => $prediksiProduk
        ]);
    }
}