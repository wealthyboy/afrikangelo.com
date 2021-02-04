<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatPrice;


class Size extends Model
{   
    
    use FormatPrice;

    public $appends = ['customer_price','converted_price','currency'];


    protected $fillable=['name','price'];


    public function subjects()
    {
       return $this->belongsToMany('App\Subject','size_subject');
    }


    public function frames()
    {
        return $this->belongsToMany('App\Frame','frame_size');
    }


}
