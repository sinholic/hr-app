<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    public $name           =   'Role';
    public $log_model      =   'App\Models\Role';
    public $back_from_list =   'roles.index';
    public $back_from_form =   'roles.index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Role::with('permissions')
        ->orderBy('name')
        ->orderBy('created_at', 'DESC')
        ->get();
        // dd($datas);
        $contents = array(
            array(
                'field'     =>  'name',
            ),
            array(
                'field'     =>  'permissions',
                'label'     =>  'Permissions',
                'type'      =>  'permission_name'
            ),
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
            'enable_add'                =>  true,
            'enable_delete'             =>  false,
            'enable_edit'               =>  true,
            'enable_action'             =>  true
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
        $permissions        = Permission::pluck('name', 'id');
        $contents   = array(
            array(
                'field'     =>  'permissions[]',
                'label'     =>  'Permissions',
                'type'      =>  'select2_multiple',
                'data'      =>  $permissions
            ),
            array(
                'field'     =>  'name',
                'type'      =>  'text',
            )
        );
        return view('page.content.add')
        ->with('contents', $contents);
    }

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
                'name'  =>  'required'
            ]
        );
        $data           =   $request->all();
        $myPermissions  =   $request->permissions;
        $permissions    =   [];
        unset($data['permissions']);
        $role           =   Role::create($data);
        if (isset($myPermissions)) {
            foreach ($myPermissions as $key => $value) {
                $permissions[]    =   Permission::find($value);
            }
        }
        $role->syncPermissions($permissions);
        return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Added Successfully");
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
    public function edit(Role $model)
    {
        $permissions        = Permission::pluck('name', 'id');
        $contents   = array(
            array(
                'field'     =>  'permissions[]',
                'label'     =>  'Permissions',
                'type'      =>  'select2_multiple',
                'data'      =>  $permissions
            ),
            array(
                'field'     =>  'name',
                'type'      =>  'text',
            )
        );
        return view('page.content.edit')
        ->with('model', $model)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $model)
    {
        $data           =   $request->all();
        $myPermissions  =   $request->permissions;
        $permissions    =   [];
        unset($data['permissions']);
        $model->update($data);
        if (isset($myPermissions)) {
            foreach ($myPermissions as $key => $value) {
                $permissions[]    =   Permission::find($value);
            }
        }
        $model->syncPermissions($permissions);
        return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Updated Successfully");
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
