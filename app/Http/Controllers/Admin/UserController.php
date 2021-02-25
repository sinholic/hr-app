<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Option;
use App\Models\Role;

class UserController extends Controller
{
    private $name           =   'User';
    private $log_model      =   'App\Models\User';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = User::with([
            'recruitment_requests',
            'recruitment_change_approvals',
            'department',
        ])
        ->orderBy('created_at','DESC')
        ->get();
        $contents = array(
            [
                'field'     =>  'name',
            ],
            [
                'field'     =>  'email',
                'label'     =>  'E-mail',
            ],
            [
                'field'     =>  'department',
                'key'       =>  'name'
            ]
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
            'enable_add'                =>  true,
            'enable_delete'             =>  true,
            'enable_edit'               =>  true,
            'enable_action'             =>  true,
            // 'button_extends'            =>  
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
        $departments        =   Option::where('type', 'DEPARTMENT')->pluck('name', 'id');
        $roles              =   Role::pluck('name', 'id');
        $contents   = array(
            [
                'field'     =>  'name',
                'type'      =>  'text',
            ],
            [
                'field'     =>  'email',
                'type'      =>  'text',
                'label'     =>  'E-mail',
            ],
            [
                'field'     =>  'password',
                'type'      =>  'password',
            ],
            [
                'field'     =>  'department_id',
                'label'     =>  'Department',
                'type'      =>  'select2',
                'data'      =>  $departments
            ],
            [
                'field'     =>  'role_id',
                'label'     =>  'Role',
                'type'      =>  'select2',
                'data'      =>  $roles
            ]
        );
        return view('page.content.add')
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\User  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(User $model, Request $request)
    {
        $departments        =   Option::where('type', 'DEPARTMENT')->pluck('name', 'id');
        $roles              =   Role::pluck('name', 'id');
        $contents   = array(
            [
                'field'     =>  'name',
                'type'      =>  'text',
                'state'     =>  'readonly'
            ],
            [
                'field'     =>  'email',
                'type'      =>  'text',
                'label'     =>  'E-mail',
                'state'     =>  'readonly'
            ],
            [
                'field'     =>  'password',
                'type'      =>  'password'
            ],
            [
                'field'     =>  'department_id',
                'label'     =>  'Department',
                'type'      =>  'select2',
                'data'      =>  $departments
            ],
            [
                'field'     =>  'role_id',
                'label'     =>  'Role',
                'type'      =>  'select2',
                'data'      =>  $roles,
                'value'     =>  $model->roles[0]->id ?? NULL
            ]
        );
        return view('page.content.edit')
        ->with('model', $model)
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
                'name'          =>  'required',
                'email'         =>  'required',
                'password'      =>  'required',
                'role_id'       =>  'required',
            ]
        );
        $data = $request->all();
        $data['password']       =   bcrypt($request->password);
        unset($data['role_id']);
        $user   = User::create($data);
        $role   = Role::find($request->role_id);
        $user->syncRoles($role);

        return redirect()->route("users.index")->withSuccess("Users has been Added Successfully");

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $model)
    {
        $request->validate(
            [
                'role_id'           =>  'required',
            ]
        );
        $data   = $request->all();
        unset($data['role_id']);
        unset($data['password']);
        if ($request->password) {
            $data['password']       =   \Hash::make($request->password);
        }
        $model->update($data);
        $role                       = Role::find($request->role_id);
        $model->syncRoles($role);
        

        return redirect()->route("users.index")->withSuccess("User has been Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $model)
    {
        if (!$model) {
            return redirect()->route("users.index")->withWarning("$this->name Not Found / has been Deleted");
        }

        $model->delete();

        return redirect()->route("users.index")->withSuccess("$this->name has been Deleted Successfully");
    }
}
