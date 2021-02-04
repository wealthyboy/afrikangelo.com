<?php

namespace App\Http\Controllers\UserProducts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Attribute;
use App\Subject;
use Illuminate\Validation\Rule;
use App\Http\Helper;
use App\SystemSetting;
use App\ProductVariation;
use App\Image;




class UserProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products  = optional(auth()->user())->products;
        return view('account.products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $categories =  Category::parents()->where("type","art")->get();
        $subjects   = Subject::art()->get();
        $medium    =  Attribute::where("name","Mediums")->first();
        $material  =  Attribute::where("name","Materials")->first();
        $style     =  Attribute::where("name","styles")->first();
        return view('account.products.create',compact('categories','subjects','medium','material','style'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            "category_id"  => "required",
            'title'=>[
                'required',
                    Rule::unique('products')->where(function ($query) use ($request) {
                        $query->where('deleted_at','=',null);
                    }) 
            ],
        ]);


        $image  = $request->image;

        try {
        	$img = \Image::make($request->image);
        } catch (Exception $e) {
        	return redirect()->back()->withErrors(['error'=>'We could not process your request.']);
        }

        $product = new Product();

	    // pick a color at position as array
        $color = $img->pickColor(10, 50,'hex');
	    if ( $img->width() > $img->height())  { 
			$product->view =   'landscape'; 
		}  else if ( $img->width() == $img->height()){ 
			$product->view =   'square'; 
		} else { 
			$product->view =   'portrait'; 
        }
        
        $sale_price = $request->has('sale_price') ? $request->sale_price : null;
        $product->title = $request->title;
        $product->price        = $request->price;
        $product->sale_price   = $sale_price;
        $product->slug         = str_slug($request->title);
        $product->weight       = $request->weight;
        $product->user_id       = optional(auth()->user())->id;
        $product->height       = $request->height;
        $product->image        = $request->image;
        $product->width        = $request->width;
        $product->description  = $request->description;
        $product->sale_price_expires = Helper::getFormatedDate($request->sale_price_expires);
        $product->allow       = $request->allow ? $request->allow : 0;
        $product->product_type = 'simple';
        $product->packaging        =   $request->packaging;
        $product->allow_offer      =   0;
        $product->quantity  = 1;
        $product->sku = str_random(6);
        $product->save();

        $category = Category::find($request->category_id);

        $category->subjects()->sync([$request->category_id]);

        if( !empty($request->category_id) ){
            $product->categories()->sync([$request->category_id]);
        }

        if( !empty($request->mediums) ){
            $category_attribute = [];
            foreach($request->mediums as $parent_id  => $value){
                $category->attributes()->sync($parent_id);
                $category_attribute[$value] = ['parent_id'=>$parent_id]; 
            }
            $product->meta_fields()->sync($category_attribute); 
        }

        if( !empty($request->materials) ){
            $category_attribute = [];
            foreach($request->materials as $parent_id  => $value){
                $category->attributes()->syncWithoutDetaching($parent_id);
                $category_attribute[$value] = ['parent_id'=>$parent_id]; 
            }
            $product->meta_fields()->syncWithoutDetaching($category_attribute); 
        }

        //$this->syncCategoryAttributes();

        if( !empty($request->styles) ){
            $category_attribute = [];
            foreach($request->styles as $parent_id  => $value){
                $category->attributes()->syncWithoutDetaching($parent_id);
                $category_attribute[$value] = ['parent_id'=>$parent_id]; 
            }
            $product->meta_fields()->syncWithoutDetaching($category_attribute); 
        }

        if( !empty($request->subject_id) ){
            $product->subjects()->sync($request->subject_id);
        }

        return back()->with("success","Your item has been uploaded succesfully. Please give a moment to review");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
