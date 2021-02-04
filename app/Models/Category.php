<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasChildren;

class Category extends Model
{
    use HasChildren;
	
	protected $fillable = ['category_name','description','slug','parent_id','sort_order','allow'];
	

    public function children()
    {
        return $this->hasMany('App\Models\Category','parent_id','id')->orderBy('sort_order','asc');
    }

    public function images()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')->where('allow',1);
    }


    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject');
    }


    public function discount()
    {
        return $this->hasOne('App\Models\Discount');
    }


    public function link()
    {
        return $this->type == 'photo_to_art' ? '/photo-to-art/'.$this->slug : "/products";
    }


    public function product_variations()
    {
        return $this->belongsToMany('App\Models\ProductVariation');
    }


    public function product_variants()
    {
        return $this->belongsToMany('App\Models\ProductVariation')->whereNotNull('name');
    }


    public function isCategoryHaveMultipleChildren()
    {   
        $names = [];
        if ( $this->children->count()) {
            foreach (  $this->children as $children){
                if ( $children->children->count()) {
                    foreach (  $children->children as $children){
                       $names[] = $children->name;
                    }
                }
            }
        }

        return !empty($names) ? true : false;
    }


    public function attributes()
    {
        return $this->belongsToMany('App\Models\Attribute')
                    ->withPivot('id');
    }


    public function attribute_parents()
    {
        return $this->hasMany('App\Models\AttributeCategory')->whereNull('parent_id');
    }


    public function parent_attributes()
    {
        return $this->belongsToMany('App\Attribute')
                    ->wherePivot('parent_id',null)
                    ->withPivot('id');
    }


    public function check($collections,$id)
    {
        foreach($collections as $collection){
            if(null !== $collection->id && $collection->id == $id ){
                return $collection->id;
            }
        }
        return false;
    }



    public function getRouteKeyName(){
        return 'slug';
    }


   
}
