<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasChildren;
use App\Traits\FormatPrice;



class Shipping extends Model
{
    use HasChildren,FormatPrice;

    protected $fillable = ['name','parent_id'];

    public $appends = [
        'converted_price',
    ];
	

    public function children()
    {
        return $this->hasMany(Shipping::class,'parent_id','id');
    }

    public function parent()
    {
        return $this->belongsTo(Shipping::class,'parent_id','id');
    }


    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    

    public function locations()
    {
        return $this->belongsToMany(Shipping::class,'location_shipping');
    }
    

}
