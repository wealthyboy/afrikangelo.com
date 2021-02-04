<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public function email_list(){
        return $this->belongsTo(EmailList::class);
    }
}
