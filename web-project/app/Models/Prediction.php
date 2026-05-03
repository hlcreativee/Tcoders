<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prediction extends Model
{
    use HasFactory;

    // Nama tabel di database (opsional jika nama sudah jamak)
    protected $table = 'predictions';

    // Field yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'product_id',
        'created_by',
        'prediction_date',
        'predicted_quantity',
        'model_used'
    ];

    /**
     * Relasi ke model Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}