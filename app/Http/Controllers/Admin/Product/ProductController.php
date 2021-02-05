<?php 


namespace App\Http\Controllers\Admin\Product;


use App\User;

use App\Models\Image;
use App\Models\Product;
use App\Models\Activity;
use App\Models\Category;
use App\Models\SystemSetting;
use App\Models\RelatedProduct;
use App\Models\AttributeProduct;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;
use App\Models\Subject;
use App\Models\Http\Helper;


use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;




class ProductController extends Controller
{
    
    protected $settings;

    public function __construct()
    {	  
	  $this->settings =  SystemSetting::first();
    }

    /**
     * Display a listing of the resource.
     *
     * return \Illuminate\Http\Response
     */
    public function index()
    {   
        $products = Product::with('categories')
                            ->photoToArt()
                            ->orderBy('created_at','desc')
                            ->paginate(30);
        
        return view('admin.products.index',compact('products'));
    }


    

    public function loadAttr(Request $request){
        $attribute_id = array_filter($request->attribute_ids);
        if (empty($attribute_id)){
            return response()->json([
                'error' => 'Please select at least 1 attribute'
            ],422);
        }
        $product_attributes = Attribute::find($attribute_id);
        $counter = rand(1,500);
        return view('admin.products.variation',compact('counter','product_attributes'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * return \Illuminate\Http\Response
     */
    public function create()
    {
        User::canTakeAction(2);
        $subjects = Subject::photoToArt()->get();
        $categories = Category::photoToArt()->get();
        return view('admin.products.create',compact('subjects','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * param  \Illuminate\Http\Request  $request
     * return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product)
    {   
        
        $this->validate($request,[
            "category_id"  => "required|array",
            'title'=>[
                'required',
                    Rule::unique('products')->where(function ($query) use ($request) {
                        $query->where('deleted_at','=',null);
                    }) 
            ],
        ]);


        $image  = $request->image;
        $product_variation_id = [];

        $sale_price = $request->has('sale_price') ? $request->sale_price : null;
        $product->title = $request->title;
        $product->price        = $request->price;
        $product->sale_price   = $sale_price;
        $product->slug         = str_slug($request->title);
        $product->weight       = $request->weight;
        $product->height       = $request->height;
        $product->image        = $request->image;
        $product->width        = $request->width;
        $product->description  = $request->description;
        $product->sale_price_expires = Helper::getFormatedDate($request->sale_price_expires);
        $product->allow       = $request->allow ? $request->allow : 0;
        $product->total = 2;
        $product->featured=  $request->featured_product ? 1 : 0;
        $product->product_type = 'photo_to_art';
        $product->quantity  = $request->quantity;
        $product->sku = str_random(6);
        $product->save();

        if( !empty($request->category_id) ){
            $product->categories()->sync($request->category_id);
        }

        if( !empty($request->subject_id) ){
            $product->subjects()->sync($request->subject_id);
        }
        
        /**
         * Save related products
        */
        if( !empty($request->related_products) ){
            foreach ($request->related_products as $key => $product_ids) {
                $product->related_products()->create([
                    'related_id' =>  $product_ids,
                    'sort_order' =>$request->sort_order[$key],
                ]);
            }
        }

        

        $images = null;
        if (!empty($request->images) ) {
            $images =  $request->images;
            $images = array_filter($images);
            foreach ( $images as $variation_image) {
                $images = new Image(['image' => $variation_image]);
                $product->images()->save($images);
            }
        } 
        
    
        (new Activity)->Log("Created a new product {$request->product_name}");
        return \Redirect::to('/admin/products');
    }


   


    public function search(Request $request){
        $filtered_array = $request->only(['q', 'field']);
		if (empty( $filtered_array['q'] ) )  { 
			return redirect('/errors');
		}
		if($request->has('q')){
			$filtered_array = array_filter($filtered_array);
			

                $query = Product::whereHas('categories', function( $query ) use ( $filtered_array ){
                    $query->where('categories.name','like','%' .$filtered_array['q'] . '%')
                        ->orWhere('products.product_name', 'like', '%' .$filtered_array['q'] . '%')
                        ->orWhere('products.sku', 'like', '%' .$filtered_array['q'] . '%');


                })->orWhereHas('variants', function( $query ) use ( $filtered_array ){
                    $query->where('product_variations.name', 'like', '%' .$filtered_array['q'] . '%')
                    ->orWhere('product_variations.sku', 'like', '%' .$filtered_array['q'] . '%');
                });
        }
			
        $products = $query->groupBy('products.id')->paginate(10);
        $products->appends(request()->all());

        return view('admin.products.index',compact('products'));  
    }

    /**
     * Display the specified resource.
     *
     * param  \App\Product  $product
     * return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product=Product::find($id);
        //$product_images = $product->product_images->slice(1);
        return view('admin.products.show',compact('other_sizes','product_images','sizes','product'));
    }


    public function getRelatedProducts(Request $request){
        if ($request->filled('product_name')){
            $products = ProductVariation::where('name', 'like', '%' . $request->product_name . '%')
            ->take(10)
            ->get();
            return view('admin.products.related_products',compact('products'));  
        }
        return [];
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * param  \App\Product  $product
     * return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        User::canTakeAction(3);
        $subjects = Subject::photoToArt()->get();
        $categories = Category::photoToArt()->get();
        $product = Product::find($id);
        $helper  = new Helper;
        return view('admin.products.edit',compact('helper','product','subjects','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * param  \Illuminate\Http\Request  $request
     * param  \App\Product  $product
     * return \Illuminate\Http\Response
     */
    public function update(Request $request,$id) 
    { 

        $this->validate($request,[
            "category_id"  => "required|array",
            'title'=>[
                'required',
                    Rule::unique('products')->where(function ($query)  {
                    $query->where('deleted_at', '=', null);
                    })->ignore($id) 
            ],
        ]);


        $product = Product::findOrFail($id);
        $image  = $request->image;
       
        
        $sale_price = $request->has('sale_price') ? $request->sale_price : null;
        $product->title = $request->title;
        $product->price = $request->price;
        $product->sale_price = $sale_price;
        $product->sale_price_expires = Helper::getFormatedDate($request->sale_price_expires);
        $product->slug        =  str_slug($request->title);
        $product->weight      = $request->weight;
        $product->height      = $request->height;
        $product->image       = $request->image;
        $product->width       = $request->width;
        $product->description = $request->description;
        $product->allow       = $request->allow ? $request->allow : 0;
        $product->brand_id    = $request->brand_id;
        $product->total       = 2;
        $product->product_type = 'photo_to_art';
        $product->featured    =  $request->featured_product ? 1 : 0;
        $product->pending     = 0;
        $product->quantity    = $request->quantity;
        $product->sku         = str_random(6);
        $product->save();

        if( !empty($request->category_id) ){
            $product->categories()->sync($request->category_id);
        }

        if( !empty($request->subject_id) ){
            $product->subjects()->sync($request->subject_id);
        }

        if (!empty($request->images) ) {
            $images = array_filter($request->images);
            foreach ( $images as $variation_image) {
                $images = new Image(['image' => $variation_image]);
                $product->images()->save($images);
            }
        } 
        

        if(!empty($request->related_products)){
            foreach ($request->related_products as $related_product_id => $product_ids) {
                $product->related_products()->updateOrCreate(
                    [
                        'id' =>  $related_product_id
                    ],
                    [
                    'related_id' =>  $product_ids,
                    'sort_order' =>  $request->sort_order[$related_product_id],
                    ]
                );
            }
        }

    
        return \Redirect::to('/admin/products');
    }


    

    public function synAttributesCategories($request,$parent_id)
    {
        $cA = [];
        $categories = Category::find($request->category_id);
        foreach ($categories as $category) {
            $category->attributes()->syncWithoutDetaching($parent_id);
            foreach($category->parent_attributes as $attribute){
                if($parent_id === $attribute->id){ 
                    $cA[$attribute->pivot->id][] = $id;
                } 
            }
        }

        return $cA;
    }

 

    public function syncProductVariationValues($filtered_attributes,$product_variation,$product)
    {
        $names = [];

        foreach ($filtered_attributes  as  $parent_id => $attribute_id) 
        {   
            if ( $attribute_id == null ){
                continue;
            }
            $attribute = Attribute::find($attribute_id);
            $names[]=$attribute->name;
            $product_variation->attribute_name = explode('_',implode('_', $names))[0]; 

            $product_variation->save(); 
            $product_variation->product_variation_values()->create([
                'attribute_parent_id' => $parent_id,
                'attribute_id' => $attribute_id,
                'name' => $attribute->name,
                'product_id' => $product->id
            ]);
        }  
    }



     public function destroyVariation(Request $request,$product_variation_id)
     {
        $product_variation_values = ProductVariationValue::whereIn('product_variation_id',[$product_variation_id])->get();
        foreach ($product_variation_values as $product_variation_value) {
            $product_variation_value->product->attributes()->detach([$product_variation_value->attribute_id]);
        }
        $product_variation = ProductVariation::find($product_variation_id);
        $product = $product_variation->product;
        ProductVariation::destroy( $product_variation_id );
        if (!$product->variants->count()){
           $product->has_variants = false;
           $product->save();
        }
        return response('done',200);
     }

     public function destroyRelatedProduct(Request $request,$id)
     {
        RelatedProduct::destroy( $id );
        return response('done',200);
     }

   

    /**
     * Remove the specified resource from storage.
     *
     * param  \App\Product  $product
     * return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        User::canTakeAction(5);
        $rules = array (
                '_token' => 'required' 
        );
        $validator = \Validator::make ( $request->all (), $rules );
        if (empty ( $request->selected )) {
            $validator->getMessageBag ()->add ( 'Selected', 'Nothing to Delete' );
            return \Redirect::back ()->withErrors ( $validator )->withInput ();
        }
        $count = count($request->selected);
        (new Activity)->Log("Deleted  {$count} Products");

        foreach ( $request->selected as $selected ){
            $delete = Product::find($selected);
            $delete->variants()->delete();
            $delete->variant()->delete();
            $delete->delete();
        }
        
        return redirect()->back();
    }
}
