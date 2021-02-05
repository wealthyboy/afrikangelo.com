<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariationValue extends Model
{   
    protected $fillable = [
        'attribute_parent_id',
        'name',
        'attribute_id',
        'product_variation_id',
        'product_id',
    ];

    public function product_variation(){
        return $this->belongsTo(ProductVariation::class);
    }


    public function product_variation_attribute(){
        return $this->belongsTo(ProductVariationAttribute::class);
    }

    
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function attribute(){
        return $this->belongsTo(Attribute::class);
    }

    public function parent_attribute(){
        return $this->belongsTo(Attribute::class,'attribute_parent_id');
    }
}
