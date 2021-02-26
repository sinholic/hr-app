<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public $name           =   'Department';
    public $log_model      =   'App\Models\Option';
    public $back_from_list =   'departments.index';
    public $back_from_form =   'departments.index';
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'type'          =>  'required',
                'name'          =>  'required|unique:options,name',
            ]
        );
        $data = $request->all();
        Option::create($data);

        return redirect()->route("users.index")->withSuccess("$this->name has been Added Successfully");

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Option  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Option $model)
    {
        $request->validate(
            [
                'type'          =>  'required',
                'name'          =>  'required',
            ]
        );
        $data = $request->all();
        $model->update($data);        
        
        return redirect()->route("users.index")->withSuccess("$this->name has been Updated Successfully");
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Option::where('type', 'DEPARTMENT')
        ->orderBy('type','ASC')
        ->orderBy('created_at','DESC')
        ->get();
        $contents = array(
            array(
                'field'     =>  'type',
                'label'     =>  'Category',
            ),
            array(
                'field'     =>  'name'
            ),
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
            'enable_add'                =>  true,
            'enable_delete'             =>  false,
            'enable_edit'               =>  true,
            'enable_action'             =>  true,
        );
        return view('page.content.index')
        ->with('datas', $datas)
        ->with('contents', $contents)
        ->with('view_options', $view_options);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contents = array(
            array(
                'field'     =>  'type',
                'label'     =>  'Category',
                'type'      =>  'text',
                'state'     =>  'readonly',
                'value'     =>  'DEPARTMENT'
            ),
            array(
                'field'     =>  'name',
                'type'      =>  'text'
            ),
        );
        return view('page.content.add')
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Option  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Option $model, Request $request)
    {
        $contents = array(
            array(
                'field'     =>  'type',
                'label'     =>  'Category',
                'type'      =>  'text',
                'state'     =>  'readonly',
                'value'     =>  'DEPARTMENT'
            ),
            array(
                'field'     =>  'name',
                'type'      =>  'text'
            ),
        );
        return view('page.content.edit')
        ->with('model', $model)
        ->with('contents', $contents);
    }
}
