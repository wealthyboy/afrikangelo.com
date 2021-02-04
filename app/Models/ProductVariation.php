<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\SystemSetting;
use App\Traits\FormatPrice;
use App\Traits\ImageFiles;
use App\Http\Helper;
use App\CategoryProductVariation;
use App\Filters\ProductsFilter\ProductFilters;
use Illuminate\Database\Eloquent\Builder;






class ProductVariation extends Model
{   

	use FormatPrice,ImageFiles;

    protected $setting;

    public $folder = 'products';


    protected $fillable = [
        'name',
        'price',
        'sale_price',
        'product_id',
        'quantity',
        'image',
        'sku',
        'width',
        'length',
        'weight',
        'sale_price_expires',
        'image_m',
		'image_tn',
		'image_to_show_m',
        'image_to_show_tn',
        'link',
        'slug'
    ];

    protected $dates = [
        'sale_price_expires'
    ];

    public $appends = [
		'discounted_price',
        'currency',
        'variation',
        'converted_price',
        'customer_price',
        'image_to_show',
        'default_discounted_price',
        'percentage_off',
        'default_percentage_off',
		'image_m',
        'image_tn',
        'image_to_show_m',
        'link',
        'image_to_show_tn',
        'active_color'
	];


    public function __construct(){
		$this->setting = SystemSetting::first();
    }
    
    
    public function product(){
        return $this->belongsTo('App\Product');
    }


    public function categories(){
        return $this->belongsToMany('App\Category')->withPivot('category_id');
    }


    public function meta_fields()
    {
		return  $this->belongsToMany('App\Attribute','attribute_product_variation','product_variation_id', 'attribute_id')
					->groupBy('attribute_id')
					->withPivot([
						'attribute_id',
					])
					->withPivot('id');
	}


    public function link()
	{   
        $slug = $this->categories->count() ? $this->categories->first()->slug : null;
		$link  = '/product/';
		$link .=  optional(optional($this->category)->category)->slug ? 
		          optional(optional($this->category)->category)->slug .'/' :
		          $slug .'/';
		$link .= $this->slug;
		return $link;
	}


    public function scopeFilter(Builder $builder,$request,array $filters = []){
        return (new ProductFilters($request))->add($filters)->filter($builder);
    }



	public function category(){
		return $this->hasOne(CategoryProductVariation::class)->where('category_id',optional($this->pivot)->category_id);
    }

    public function product_variation_values()
    {
        return $this->hasMany('App\ProductVariationValue');
    }


    public function product_variation_attributes()
    {
        return $this->hasMany('App\ProductVariationAttribute');
    }


    public static function getTotalValue() {
		return static::sum(function($product_variantions) {
		   return $product_variantions->quantity * $product_variantions->price;
		});
    }


    public function related_products()
    {
        return $this->hasMany(RelatedProduct::class);
	}
    
    
    public function product_variation_value()
    {
        return $this->hasOne('App\ProductVariationValue');
    }


    public function product_variation_attribute()
    {
        return $this->hasOne('App\ProductVariationAttribute');
    }

    public function getRouteKeyName(){
        return 'slug';
    }


    public function product_variation_names(){
       return $this->product_variation_values;
    }

    

    public function images()
    {
        return $this->morphMany('App\Image', 'imageable')->orderBy('created_at','asc');
    }

    public function img()
    {
        return $this->hasOne('App\Image','imageable_id','id');
    }

    public function cart()
    {
        return $this->hasOne('App\Cart');
    }

    public function carts()
    {
        return $this->hasMany('App\Cart');
    }


    public function getActiveColorAttribute()
	{
		return optional($this->product_variation_value)->attribute;
	}


   
    
    public function getVariationAttribute(){
		return $this->product_variation_names();
    }

    
    public function getCustomerPriceAttribute(){
		return $this->discounted_price ?? $this->converted_price ;
    }
    
    public function getLinkAttribute(){
		return $this->link();
    }
    
    

}
