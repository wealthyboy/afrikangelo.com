<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasChildren;

class Location extends Model
{
    use HasChildren;
	
	protected $fillable = ['name','parent_id'];
	
    public function children()
    {
        return $this->hasMany(Location::class,'parent_id','id');
    }

    public function shipping()
    {
        return $this->hasMany(Shipping::class);
    }

    public function shippings()
    {
        return $this->belongsToMany(Location::class,'location_shipping');

	}
}
