<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $data = DB::table('transaksi')
            ->select(
                'Invoice',
                'StockCode',
                'Description',
                'Quantity',
                'InvoiceDate',
                'Price',
                'CustomerID',
                'Country'
            )
            ->whereNotNull('InvoiceDate')
            ->orderBy('InvoiceDate', 'desc')
            ->paginate(50);

        return view('transaksi', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'InvoiceDate' => 'required',
            'Description' => 'required',
            'Quantity' => 'required|numeric',
            'Price' => 'required|numeric',
        ]);

        DB::table('transaksi')->insert([
            'Invoice' => 'INV-' . time(),     
            'StockCode' => 'AUTO',               
            'Description' => $request->Description,
            'Quantity' => $request->Quantity,
            'InvoiceDate' => $request->InvoiceDate,
            'Price' => $request->Price,
            'CustomerID' => 1,                  
            'Country' => 'Indonesia',           
        ]);

        return redirect()->route('transaksi.index')
            ->with('success', 'Data berhasil ditambahkan');
    }
}