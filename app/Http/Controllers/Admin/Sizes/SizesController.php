<?php

namespace App\Http\Controllers\Admin\Sizes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Size;
use App\User;
use Illuminate\Validation\Rule;
use App\Subject;
use App\Activity;




class SizesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = Size::all();
        $subjects = Subject::photoToArt()->get();
        return view('admin.sizes.index',compact('subjects','sizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //define validation 
        $this->validate($request, [
            'name' => 'required|unique:sizes',
            'price' => 'required',
		]);
        
        $size = new Size;
        $size->name = $request->name;
        $size->price = $request->price;
        $size->save();
        if ( !empty($request->subject_id) ){
            $size->subjects()->sync($request->subject_id);
        }
        (new Activity)->Log("Created a new size called {$request->name}");
        return redirect()->back();
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
        User::canTakeAction(4);
        $size = Size::find($id);
        $subjects = Subject::photoToArt()->get();
        return view('admin.sizes.edit',compact('subjects','size'));
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
        $size = Size::find($id);
        
 
        $this->validate($request,[
            'name'=>[
                'required',
                    Rule::unique('sizes')->ignore($id)     
            ],
        ]);

        $size->name=$request->name;
        $size->price=$request->price;        
        $size->save();
    
        if ( !empty($request->subject_id) ){
            $size->subjects()->sync($request->subject_id);
        }
        (new Activity)->Log("Created a new size called {$request->name}");
        return redirect()->action('Admin\Sizes\SizesController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        User::canTakeAction(5);
		$rules = array(
				'_token' => 'required',
		);
		$validator = \Validator::make($request->all(),$rules);
		if ( empty ( $request->selected)) {
			$validator->getMessageBag()->add('Selected', 'Nothing to Delete');
			return \Redirect::back()
			->withErrors($validator)
			->withInput();
		}
		Size::destroy($request->selected);  	
		
		return redirect()->back();
    }
}
