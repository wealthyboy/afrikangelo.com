<?php

namespace App\Http\Controllers\Admin\Subjects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subject;
use App\Size;
use Illuminate\Validation\Rule;
use App\Activity;
use App\User;



class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::photoToArt()->get();
        $sizes = Size::all();
        return view('admin.subjects.index',compact('subjects','sizes'));
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
            'name' => 'required|unique:subjects',
            'price' => 'required',
            'type' => 'required'
		]);
        
        $subject = new Subject;
        $subject->name = $request->name;
        $subject->price = $request->price;
        $subject->type = $request->type;
        $subject->save();
        if ( !empty($request->size_id) ){
            $subject->sizes()->sync($request->size_id);
        }
        (new Activity)->Log("Created a new subject called {$request->name}");
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
        $subject = Subject::find($id);
        $sizes = Size::all();
        return view('admin.subjects.edit',compact('sizes','subject'));
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
        $subject = Subject::find($id);
        
 
        $this->validate($request,[
            'name'=>[
                'required',
                    Rule::unique('subjects')->ignore($id)     
            ],
        ]);

        $subject->name=$request->name;
        $subject->price=$request->price;  
        $subject->type=$request->type; 
        $subject->save();

        if ( !empty($request->size_id) ){
            $subject->sizes()->sync($request->size_id);
        }
    
        //Log Activity
        //(new Activity)->Log("Updated  Category {$request->name} ");
        return redirect()->action('Admin\Subjects\SubjectsController@index');
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
		Subject::destroy($request->selected);  	
		
		return redirect()->back();
    }
}
