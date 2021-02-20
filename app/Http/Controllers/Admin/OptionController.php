<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OptionController extends Controller
{
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
                'name'          =>  'required',
            ]
        );
        $data = $request->all();
        Option::create($data);

        return redirect()->route("users.index")->withSuccess("Option has been Added Successfully");

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
        
        return redirect()->route("users.index")->withSuccess("Option has been Updated Successfully");
    }
}
