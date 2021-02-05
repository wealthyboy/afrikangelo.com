<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasChildren;


class ProductAttribute extends Model
{   

    use HasChildren;

    protected $fillable = [
        'attribute_id',
        'product_id',
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

    public function children()
    {
        return $this->hasMany(ProductAttribute::class,'parent_id','id');
    }

    public function isAChild($id)
    {
        foreach ($this->children() as $key => $value) {
            if($value->attribute_id == $id){
              return 'selected';
            } 
        }

        return '';
    }

    public function parent()
    {
        return $this->hasOne(ProductAttribute::class,'parent_id');
    }

    
}
