<?php

namespace App\Http\Controllers\Admin;

use App\Models\Recruitment;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Recruitment::with([
            'department',
            'job_position',
            'user_requested',
            'user_change_status',
            'user_processed',
            'priority',
            'request_status',
            'process_status',
            'candidates',
        ])
        ->orderBy('created_at','DESC')
        ->get();
        $contents = array(
            array(
                'field'     =>  'department',
                'key'       =>  'name'
            ),
            array(
                'field'     =>  'job_position',
                'key'       =>  'name',
                'label'     =>  'Position'
            ),
            array(
                'field'     =>  'number_of_people_requested',
                'label'     =>  '# Request people'
            ),
            array(
                'field'     =>  'number_of_people_approved',
                'label'     =>  '# Approved people'
            ),
            array(
                'field'     =>  'priority',
                'key'       =>  'name'
            ),
            array(
                'field'     =>  'request_status',
                'key'       =>  'name'
            ),
            array(
                'field'     =>  'process_status',
                'key'       =>  'name'
            ),
            array(
                'field'     =>  'candidates',
                'type'      =>  'count'
            ),
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
            'enable_add'                =>  true,
            'enable_delete'             =>  false,
            'enable_edit'               =>  false,
            'enable_action'             =>  true,
            'button_extends'            =>  array(
                array(
                    'label'                 =>  'approve',  // Button text to be shown in the HTML
                    'action'                =>  'recruitments.approve', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'success',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','Management'], // Roles to be checked for the UI to be show
                    'when'                  =>  'request_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR APPROVAL' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'adjustment',  // Button text to be shown in the HTML
                    'action'                =>  'recruitments.adjustment', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'warning',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','Management'], // Roles to be checked for the UI to be show
                    'when'                  =>  'request_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR APPROVAL' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'reject',   // Button text to be shown in the HTML
                    'action'                =>  'recruitments.reject', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'danger',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','Management'], // Roles to be checked for the UI to be show
                    'when'                  =>  'request_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR APPROVAL' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'add candidate',    // Button text to be shown in the HTML
                    'action'                =>  'candidates.create', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'warning',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'request_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'APPROVED' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'view candidates',  // Button text to be shown in the HTML
                    'roles'                 =>  ['Super Admin','HR Manager', 'Manager', 'Team Lead'], // Roles to be checked for the UI to be show
                    'action'                =>  'candidates.view', // Routes to action, eg : dashboard.index, user.create
                    'when'                  =>  'request_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'APPROVED' // Value that right for the condition
                )
            )
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
        $jobpositions       =   Option::where('type', 'JOB_POSITION')->pluck('name', 'id');
        $priorities         =   Option::where('type', 'PRIORITY')->pluck('name', 'id');
        $requestStatus      =   Option::firstWhere([
            'type'  =>  'REQUEST_STATUS',
            'name'  =>  'WAITING FOR APPROVAL'
        ])->id;
        $processStatus      =   Option::firstWhere([
            'type'  =>  'PROCESS_STATUS',
            'name'  =>  'NOT YET PROCESSED'
        ])->id;
        $contents   = array(
            array(
                'field'     =>  'department_id',
                'type'      =>  'select2',
                'data'      =>  $departments
            ),
            array(
                'field'     =>  'jobposition_id',
                'type'      =>  'select2',
                'data'      =>  $jobpositions
            ),
            array(
                'field'     =>  'number_of_people_requested',
                'type'      =>  'number',
            ),
            array(
                'field'     =>  'requirements',
                'type'      =>  'wsywig',
            ),
            array(
                'field'     =>  'deadline',
                'type'      =>  'date',
            ),
            array(
                'field'     =>  'sallary_proposed',
                'type'      =>  'currency',
            ),
            array(
                'field'     =>  'priority_id',
                'type'      =>  'select2',
                'data'      =>  $priorities
            ),
            array(
                'field'     =>  'remark',
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'request_status_id',
                'type'      =>  'hidden',
                'value'     =>  $requestStatus
            ),
            array(
                'field'     =>  'requested_by_user',
                'type'      =>  'hidden',
                'value'     =>  \Auth::user()->id
            ),
            array(
                'field'     =>  'process_status_id',
                'type'      =>  'hidden',
                'value'     =>  $processStatus
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
                'department_id'                 =>  'required',
                'jobposition_id'                =>  'required',
                'number_of_people_requested'    =>  'required',
                'requirements'                  =>  'required',
                'deadline'                      =>  'required',
                'sallary_proposed'              =>  'required',
                'priority_id'                   =>  'required',
                'request_status_id'             =>  'required',
                'requested_by_user'             =>  'required',
                'process_status_id'             =>  'required',
            ]
        );
        Recruitment::create($request->all());

        return redirect()->route("recruitments.index")->withSuccess("Recruitment has been Added Successfully");

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recruitment $model)
    {
        if ($request->number_of_people_approved) {
            $request->validate(
                [
                    'number_of_people_approved'     =>  'integer|min:0',
                    'sallary_adjusted'              =>  'integer|min:0',
                ]
            );
        }
        $data = $request->all();
        if (isset($request->sallary_adjusted) && is_null($request->sallary_adjusted)) {
            $data['sallary_adjusted'] = $data['sallary_proposed'];
        }
        $model->update($data);
        

        return redirect()->route("recruitments.index")->withSuccess("Recruitment has been Updated Successfully");
    }

    /**
     * Show the form for approve the recruitment.
     *
     * @param  \App\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function approve(Recruitment $model)
    {
        $requestStatus      =   Option::firstWhere([
            'type'  =>  'REQUEST_STATUS',
            'name'  =>  'APPROVED'
        ])->id;
        $contents   = array(
            array(
                'label'     =>  'Remark for approval',
                'field'     =>  'remark',
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'request_status_id',
                'type'      =>  'hidden',
                'value'     =>  $requestStatus
            ),
            array(
                'field'     =>  'change_request_status_by_user',
                'type'      =>  'hidden',
                'value'     =>  \Auth::user()->id
            ),
        );
        return view('page.content.edit')
        ->with('model', $model)
        ->with('contents', $contents);
    }

    /**
     * Show the form for adjustment the recruitment.
     *
     * @param  \App\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function adjustment(Recruitment $model)
    {
        $requestStatus      =   Option::firstWhere([
            'type'  =>  'REQUEST_STATUS',
            'name'  =>  'APPROVED'
        ])->id;
        $contents   = array(
            array(
                'field'     =>  'number_of_people_requested',
                'type'      =>  'number',
                'state'     =>  'readonly'
            ),
            array(
                'field'     =>  'number_of_people_approved',
                'type'      =>  'number',
            ),
            array(
                'field'     =>  'sallary_proposed',
                'type'      =>  'currency',
                'state'     =>  'readonly'
            ),
            array(
                'field'     =>  'sallary_adjusted',
                'type'      =>  'currency',
            ),
            array(
                'field'     =>  'remark',
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'request_status_id',
                'type'      =>  'hidden',
                'value'     =>  $requestStatus
            ),
            array(
                'field'     =>  'change_request_status_by_user',
                'type'      =>  'hidden',
                'value'     =>  \Auth::user()->id
            ),
        );
        return view('page.content.edit')
        ->with('model', $model)
        ->with('contents', $contents);
    }

    /**
     * Show the form for reject the recruitment.
     *
     * @param  \App\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function reject(Recruitment $model)
    {
        $requestStatus      =   Option::firstWhere([
            'type'  =>  'REQUEST_STATUS',
            'name'  =>  'REJECTED'
        ])->id;
        $contents   = array(
            array(
                'label'     =>  'Reason to reject',
                'field'     =>  'remark',
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'request_status_id',
                'type'      =>  'hidden',
                'value'     =>  $requestStatus
            ),
            array(
                'field'     =>  'change_request_status_by_user',
                'type'      =>  'hidden',
                'value'     =>  \Auth::user()->id
            ),
        );
        return view('page.content.edit')
        ->with('model', $model)
        ->with('contents', $contents);
    }
}
