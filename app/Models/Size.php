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
       return $this->belongsToMany(Subject::class,'size_subject');
    }


    public function frames()
    {
        return $this->belongsToMany(Frame::class,'frame_size');
    }


}
