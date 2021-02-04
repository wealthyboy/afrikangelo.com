<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedProduct extends Model
{   
    protected $fillable= ['related_id','product_id','sort_order'];

  

    public function product_variation(){
        return $this->belongsTo(ProductVariation::class,'related_id');
    }
}
