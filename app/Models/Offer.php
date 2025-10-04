<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_klien',
        'produk_nama',
        'area_dinding',
        'volume',
        'harga_per_m2',
        'jasa_nama',
        'jasa_harga',
        'total_keseluruhan',
    ];

    public function items()
{
    return $this->hasMany(OfferItem::class);
}

public function jasaItems() {
    return $this->hasMany(OfferJasa::class);
}
}