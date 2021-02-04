<?php  

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use Auth;
use App\Models\Category;
use App\Models\User;
use App\Models\Cart;
use App\Models\SystemSetting;


use Illuminate\Support\Facades\Cache;

class   GlobalVariables { 
   
    public function compose (View $view) { 
        
		$system_settings = SystemSetting::first();
		$categories   =  Category::parents()->get();
	    $view->with([
			'system_settings'=>$system_settings,
			'categories'=>$categories,		
		]);
		
    }




}