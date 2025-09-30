<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferItem extends Model
{
    protected $fillable = ['offer_id', 'nama_produk_area', 'volume', 'harga_per_m2'];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}