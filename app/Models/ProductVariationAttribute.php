<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariationAttribute extends Model
{
    
    public function p_attribute(){
        return $this->belongsTo(Attribute::class,'attribute_parent_id');
    }


    public function attribute(){
        return $this->belongsTo(Attribute::class,'attribute_id');
    }
}
