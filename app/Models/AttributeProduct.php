<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeProduct extends Model
{
    protected $table = 'attribute_product';

    protected $fillable = [
        'product_id',
        'attribute_id',
        'parent_id',
    ];

    public function attribute()
    {
       return $this->belongsTo(Attribute::class);
    }

    public function product()
    {
       return $this->belongsTo(Product::class);
    }
   
}
