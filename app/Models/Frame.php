<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatPrice;


class Frame extends Model
{    

    use FormatPrice;

    public $appends = ['customer_price','converted_price','currency'];


    public function sizes()
    {
        return $this->belongsToMany('App\Size');
    }
}
