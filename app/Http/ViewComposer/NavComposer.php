<?php  

namespace App\Http\ViewComposer;

use Illuminate\View\View;

use Auth;
use App\Models\User;

use App\Models\Cart;
use App\Models\Information;
use App\Models\Category;
use App\Models\SystemSetting;
use App\Models\Voucher;
use App\Models\Promo;
use App\Models\Currency;

use App\Models\PageBanner;


use Illuminate\Support\Facades\Cache;

class   NavComposer { 
   
   
    public function compose (View $view) { 
		$global_categories = Category::parents('sort_order', 'asc')->get();
		$global_categories->load('products');
		//dd($global_categories);
		$footer_info = Information::where('blog',false)->parents()->get(); 
		$global_promos = Promo::where('make_live',1)->get(); 
		$system_settings = SystemSetting::first();
		$currencies = Currency::all();
		$news_letter_image = PageBanner::where('page_name','newsletter')->first();
	    $view->with([
		   	'footer_info'=>$footer_info,
		   	'global_categories'=> $global_categories,
			'system_settings'=>$system_settings,
			'global_promos'=>$global_promos,
			'news_letter_image' =>$news_letter_image,
			'currencies' =>$currencies
	    ]);
    

}




}