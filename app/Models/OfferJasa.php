<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferJasa extends Model
{
    protected $table = 'offer_jasa';
    protected $fillable = ['offer_id', 'nama_jasa', 'harga_jasa'];
}
