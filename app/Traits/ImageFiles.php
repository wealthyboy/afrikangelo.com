<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Models\SystemSetting;
use App\Http\Helper;



trait ImageFiles 
{

    protected $setting;


    public function __construct(){
        $this->setting = SystemSetting::first();
    }


    public function getImageToShowAttribute()
    {   
        return $this->image;
    }


    public function getImageToShowMAttribute()
    {   
        return $this->image_m;
    }


    public function getImageToShowTnAttribute()
    {   
        return $this->image_tn;
    }


    public function getAddImagesAttribute()
    {
        return $this->images; 
    }


    public function tn_path(){
        $image = basename($this->image);
        return  asset('images/'. $this->folder .'/tn/'.$image);
    }


    public function m_path(){
        $image = basename($this->image);
        return  asset('images/'.$this->folder.'/m/'.$image);
    }


    public function imageSize($size){
        if ( $this->image ) { 
            $image = basename($this->image);
            return asset('images/'.$this->folder.'/'. $size .'/'.$image);
        }
    }


    public function getImageMAttribute(){
        return $this->imageSize('m'); 
    }


    public function getImageTnAttribute(){
        return $this->imageSize('tn'); 
    }

}
