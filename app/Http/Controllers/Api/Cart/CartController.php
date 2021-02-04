<?php

namespace App\Http\Controllers\Api\Cart;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Product;
use App\Cart;
use App\User;
use Storage;
use App\Http\Resources\CartIndexResource;
use App\Http\Resources\CartResource;
use App\SystemSetting;
use App\Http\Helper;



class CartController  extends Controller {

	protected $settings;
	
    public function __construct()
	{
		$this->settings = SystemSetting::first();
	}
		

	public function store(Request $request) { 


		$this->validate($request,[
		   'product_id' => 'required|exists:products,id',
		   'quantity' => 'required|min:1',
		]);

		$product = Product::find($request->product_id);
		if ($product->quantity < 1) {
			return response()->json([
                'message' => [
					'errors' => "Product out of stock"
				]
			],422);
		}

		$cart = new Cart;
		if(\Auth::check())
		{   
			$cart->user_id    = $request->user()->id;
		}
		$price = $product->discounted_price ?? $product->price;
		if (\Cookie::get('cart') !== null) {
			$remember_token  = \Cookie::get('cart');
			$result = $cart->updateOrCreate(
				['product_id' => $request->product_id,'remember_token' => $remember_token],
				[
					'product_id' => $request->product_id,
					'quantity'   => $request->quantity,
					'price'      => $price,
					'total'      => $price * $request->quantity
				]
			);

			return $this->loadCart($request);
			
		}  else  {
			$value = bcrypt('^%&#*$((j1a2c3o4b5@+-40');
			session()->put('cart',$value);
			$cookie = cookie('cart',session()->get('cart'), 60*60*7);
			$cart->product_id = $request->product_id;
			$cart->quantity   = $request->quantity;
			$cart->price      = $price;
			$cart->total      = $price * $request->quantity;
			$cart->remember_token =$cookie->getValue();
			$cart->save();
			$carts = Cart::all_items_in_cart();
			$total = \DB::table('carts')->select(\DB::raw('SUM(carts.total) as items_total'))->where('remember_token',$cookie->getValue())->get();
			$sub_total =  Cart::ConvertCurrencyRate($total[0]->items_total);
			
			return response()->json([
				'data' => [
					0 => [ 
						'id' => $cart->id,
						'product_id' => $cart->product_id,
						'image'        => optional($cart->product)->image_to_show,
						'title'        => optional($cart->product)->title,
						'quantity'     => $cart->quantity,
						'price'        => $cart->converted_price,
					]
				],
				'meta' => [
					'sub_total'=>$sub_total,
					'currency' => Helper::rate()->symbol ?? optional(optional($this->settings)->currency)->symbol,
				    'currency_code' => Helper::rate()->iso_code3 ?? optional(optional($this->settings)->currency)->iso_code3,
					'user' => $request->user()
				],
			])->withCookie($cookie);
		}
    }


	public function loadCart(Request $request)
	{

		$carts = Cart::all_items_in_cart();
		$sub_total =  Cart::sum_items_in_cart();
		$rate=\Cookie::get('rate');
        return  CartIndexResource::collection($carts)->additional([
			'meta' => [
				'sub_total'=>$sub_total,
				'currency' => Helper::rate()->symbol ?? optional(optional($this->settings)->currency)->symbol,
				'currency_code' => Helper::rate()->iso_code3 ?? optional(optional($this->settings)->currency)->iso_code3,
				'user' => $request->user(),
				'isAdmin' => null !== $request->user() ? $request->user()->isAdmin() : false 
			],
        ]);
	}
	
	public function destroy(Request $request,$cart_id) { 
		
		if($request->ajax()){
			$cart =  Cart::find($cart_id);
			if ($cart && $cart->quantity > 1){
				$cart->update([
					'quantity' => ($cart->quantity - 1),
					'total' => ($cart->quantity - 1) * $cart->price
				]);
			} else {
				$cart->delete();
			}
		    return $this->loadCart($request);
		}
    }
	    
	
	
    
		
	
	
	




}