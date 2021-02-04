<?php

namespace App\Http\Controllers\Admin\Frames;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Frame;
use App\Size;
use App\Activity;
use App\User;
use Illuminate\Validation\Rule;



class FramesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $frames = Frame::all();
        $sizes = Size::all();
        return view('admin.frames.index',compact('frames','sizes'));
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
        $this->validate($request, [
            'title' => 'required|unique:frames',
            'price' => 'required',
		]);
        
        $frame = new Frame;
        $frame->title = $request->title;
        $frame->price = $request->price;
        $frame->image = $request->image;
        $frame->large_image = $request->large_image;
        $frame->save();
        if ( !empty($request->size_id) ){
            $frame->sizes()->sync($request->size_id);
        }
        (new Activity)->Log("Created a new Frame called {$request->name}");
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
        $frame = Frame::find($id);
        $sizes = Size::all();
        return view('admin.frames.edit',compact('sizes','frame'));
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
        $frame = Frame::find($id);
        $this->validate($request,[
            'title'=>[
                'required',
                 Rule::unique('frames')->ignore($id)     
            ],
        ]);
        $frame->title = $request->title;
        $frame->price = $request->price;
        $frame->image = $request->frame_image;
        $frame->large_image = $request->frame_large_image;
        $frame->save();
        if ( !empty($request->size_id) ){
            $frame->sizes()->sync($request->size_id);
        }
        //Log Activity
        //(new Activity)->Log("Updated  Category {$request->name} ");
        return redirect()->action('Admin\Frames\FramesController@index');
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
		Frame::destroy($request->selected);  	
		
		return redirect()->back();
    }
}
