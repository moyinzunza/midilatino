<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Products extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'title',
        'product_id',
        'image_url',
        'license_id',
        'download_url',
        'file_size'
    ];

    public function license(){
        return $this->belongsTo(Licenses::class, 'license_id', 'id');
    }
}
