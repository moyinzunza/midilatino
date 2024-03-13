<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewsLog extends Model
{
    protected $table = 'views_log';

    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_email',
        'license_id',
        'order_id',
        'product_id',
        'type'
    ];

    public function license(){
        return $this->belongsTo(Licenses::class, 'license_id', 'id');
    }

    public function product(){
        return $this->belongsTo(Products::class, 'product_id', 'product_id');
    }
}
