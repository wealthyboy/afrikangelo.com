<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Live;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Review;
use App\Models\Information;
use App\Models\Currency;
use App\Models\SystemSetting;
use App\Models\Http\Helper;
use Stevebauman\Location\Location;


class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {    
        $site_status =Live::first();

        $products = Product::where('featured',1)->orderBy('created_at','DESC')->take(8)->get();
        $banners = Banner::where('type','banner')->orderBy('sort_order','asc')->get();

        $sliders = Banner::where('type','slider')->orderBy('sort_order','asc')->get();
        $posts  =   Information::orderBy('created_at','DESC')->where('blog',true)->take(3)->get();
    
        if (!$site_status->make_live ) {
            return view('index',compact('products','posts','banners','sliders')); 
        } else {
            //Show site if admin is logged in
            if ( auth()->check()  && auth()->user()->isAdmin()){
                return view('index',compact('products','posts','banners','sliders')); 
            }
            return view('underconstruction.index');
        }
    }

    
}
