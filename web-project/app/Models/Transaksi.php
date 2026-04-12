<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    // Laravel default pakai "id", jadi ini sudah benar kalau kamu pakai id
    protected $primaryKey = 'id';

    public $timestamps = true; // karena kamu pakai timestamps()

    protected $fillable = [
        'Invoice',
        'StockCode',
        'Description',
        'Quantity',
        'InvoiceDate',
        'Price',
        'CustomerID',
        'Country'
    ];
}