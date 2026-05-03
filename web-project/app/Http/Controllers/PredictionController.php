<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PredictionController extends Controller
{
    public function index()
    {
        // 1. Mengambil data prediksi terbaru untuk tabel dan perhitungan card
        $predictions = Prediction::with('product')->latest()->get();
        
        // Variabel $prediksi (dicari oleh Blade untuk angka total)
        $prediksi = $predictions->sum('predicted_quantity'); 

        // Mencari produk dengan prediksi tertinggi
        $topPredict = $predictions->sortByDesc('predicted_quantity')->first();
        
        // Variabel $topProduct (dicari oleh Blade untuk teks nama produk)
        // Menggunakan 'product_name' sesuai struktur tabel products Anda
        $topProduct = $topPredict ? $topPredict->product->product_name : '-';

        // 2. Data Histori Penjualan untuk grafik "Penjualan Asli"
        // Menyesuaikan kolom 'product_name' dan 'created_at' sesuai database Anda
        $salesHistory = DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select(
                DB::raw("DATE_FORMAT(sales.created_at, '%Y-%m') as date"),
                'products.product_name as product',
                DB::raw('SUM(sales.quantity) as qty')
            )
            ->groupBy('date', 'product')
            ->orderBy('date', 'asc')
            ->get();

        // 3. Menyiapkan variabel $data untuk dikonversi menjadi JSON di Blade
        $data = $salesHistory->map(function ($item) {
            return [
                'date'    => $item->date,
                'product' => $item->product,
                'qty'     => $item->qty
            ];
        });

        // 4. Mengirimkan variabel dengan nama yang tepat sesuai kebutuhan FE
        return view('prediksi', [
            'predictions'    => $predictions,
            'prediksi'       => $prediksi,       // Untuk angka di card & JS
            'topProduct'     => $topProduct,     // Untuk teks di card
            'data'           => $data,           // Untuk grafik histori
            'prediksiProduk' => $predictions     // Untuk variabel JS prediksiProduk
        ]);
    }
}