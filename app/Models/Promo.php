<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    //

    public function promo_texts(){
        return $this->hasMany('App\PromoText');
    }
}
