<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeCategoryChildren extends Model
{
    

    protected $fillable =  ['attribute_category_id' ,'attribute_id'];


    public function attribute()
    {   
        return $this->belongsTo(Attribute::class,'attribute_id');
    }

    public function children()
    {
        return $this->hasMany(AttributeCategory::class,'parent_id','id');
    }


}
