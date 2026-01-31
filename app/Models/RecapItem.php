<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecapItem extends Model {
    protected $guarded = [];
    protected $fillable = ['recap_id', 'material', 'detail', 'harga', 'qty', 'subtotal'];

    public function recap() {
        return $this->belongsTo(Recap::class);
    }
}