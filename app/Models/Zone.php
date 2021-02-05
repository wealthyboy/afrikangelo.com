<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    //
    
	protected $fillable =[
			'name'
	];
    
	public function shipCompany(){
		return $this->belongsTo(ShipCompany::class);
	}
	
	public function  ShipCompanyDetails(){
		return $this->hasMany(ShipCompanyPrice::class);
	}
}
