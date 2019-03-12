<?php

namespace YellowProject\Http\Controllers;

use Illuminate\Http\Request;
use YellowProject\Field;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $field = Field::find($id);
        if(!$company) return redirect()->back();

        $fields = Field::where('id',$id->id)->orderBy('name');

        if ($request->input('name')) $fields->where('name', 'like', '%'.$request->input('name').'%');
        if ($request->input('type')) $fields->whereIn('type',$request->input('type'));

      //  if ($request->input('deleted')) $fields->onlyTrashed();

        return view('????')
                ->with('field',$field);
             //   ->with('fields',$fields->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
       $field = Field::find($id);
        if(!$field) return redirect()->action('FieldController@index',['field' => $field]);

        return view('???')
         //   ->with('subscriberGroups',$subscriberGroups)
            ->with('field',$field);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
         
        
         $field = Field::withTrashed()->find($id);
        if ($field)
        {          
                if($request->is_personalize == null){
                    $request['is_personalize'] = false;
                }
                if($request->is_segment == null){
                    $request['is_segment'] = false;
                }
                if($request->primary_key == null){
                    $request['primary_key'] = false;
                }
                if($request->is_required == null){
                    $request['is_required'] = false;
                }
                if($request->is_readonly == null){
                    $request['is_readonly'] = false;
                }
                if($request->is_api == null){
                    $request['is_api'] = false;
                    $request['api_url'] = null;
                }
   

                $field->name;
                $field->field_name;
                $field->type;               //list data
                $field->description;

                $field->save($request->all());

       // $field->subscriberGroups()->attach($request->subscriber_group_id);
        return redirect()->action('FieldController@index',['field' => $id]);
        //  ->with('company',$company);
        // return redirect()->action('Company\Setting\FieldController@edit', ['companyID' => $companyID, 'fieldID' => $field->id])
           // ->with('flag',"true");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $field = field::find($id);
        if(!$field) return redirect()->action('FieldController@index',['field' => $id]);

        return redirect()->action('FieldController@index',['field' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $field = Field::find($id);
        if(!$field) return redirect()->action('FieldController@index',['field' => $id]);

      //  $field = Field::find($id);
        //if(!$field) redirect()->action('FieldController@index',['companyID' => $companyID]);

       // $subscriberGroups = SubscriberGroup::whereCompanyId($companyID)->get();
        return view('??????')
                //->with('subscriberGroups',$subscriberGroups)
                //->with('company',$company)
                ->with('field',$field);    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $field = Field::find($companyID);
        if(!$field) return redirect()->action('FieldController@index',['field' => $id]);

        $field = Field::withTrashed()->find($id);
        if ($field)
        {          
                if($request->is_personalize == null){
                    $request['is_personalize'] = false;
                }
                if($request->is_segment == null){
                    $request['is_segment'] = false;
                }
                if($request->primary_key == null){
                    $request['primary_key'] = false;
                }
                if($request->is_required == null){
                    $request['is_required'] = false;
                }
                if($request->is_readonly == null){
                    $request['is_readonly'] = false;
                }
                if($request->is_api == null){
                    $request['is_api'] = false;
                    $request['api_url'] = null;
                }
                $field->name;
                $field->field_name;
                $field->type;               //list data
                $field->description;

                $field->update($request->all());


        return redirect()->action('FieldController@index',['id' => $id]);
        // return redirect()->action('Company\Setting\FieldController@edit', ['companyID' => $companyID, 'fieldID' => $id])
            //->with('flag',"true");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
      Field::destroy($id);
        return redirect('????');
    }
}
