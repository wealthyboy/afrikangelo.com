<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Helper;
use App\Traits\FormatPrice;



class Subject extends Model
{

    use FormatPrice;

    public $appends = ['customer_price','converted_price','currency'];


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'name','type',
    ];
    

    public function sizes()
    {
       return $this->belongsToMany('App\Size','size_subject');
    }

    public function scopePhotoToArt(Builder $builder){
        return $builder->where('type','photo_to_art');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Catgeory');
    }

    public function scopeArt(Builder $builder){
        return $builder->where('type','art');
    }

    public function check($collection,$id){
        return Helper::check($collection,$id);
    }
}
