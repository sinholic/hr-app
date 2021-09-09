<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class PermissionController extends Controller
{
    public $name           =   'Permission';
    public $log_model      =   'App\Models\Permission';
    public $back_from_list =   'permissions.index';
    public $back_from_form =   'permissions.index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Permission::with('roles')
        ->orderBy('created_at', 'DESC')
        ->get();
        $contents = array(
            array(
                'field'     =>  'name',
            ),
            array(
                'field'     =>  'roles',
                'label'     =>  'Roles',
                'type'      =>  'roles_name'
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
        $roles      = Role::pluck('name', 'id');
        $contents   = array(
            array(
                'field'     =>  'roles[]',
                'label'     =>  'Roles',
                'type'      =>  'select2_multiple',
                'data'      =>  $roles
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
        $myRoles        =   $request->roles;
        $roles          =   [];
        unset($data['roles']);
        $permission     =   Permission::create($data);
        foreach ($myRoles as $key => $value) {
            $roles[]    =   Role::find($value);
        }
        $permission->syncRoles($roles);
        return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Added Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $model)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $model)
    {
        $roles      = Role::pluck('name', 'id');
        $contents   = array(
            array(
                'field'     =>  'roles[]',
                'label'     =>  'Roles',
                'type'      =>  'select2_multiple',
                'data'      =>  $roles
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
    public function update(Request $request, Permission $model)
    {
        $data           =   $request->all();
        $myRoles        =   $request->roles;
        $roles          =   [];
        unset($data['roles']);
        $model->update($data);
        foreach ($myRoles as $key => $value) {
            $roles[]    =   Role::find($value);
        }
        $model->syncRoles($roles);
        return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $model)
    {
        //
    }
}
