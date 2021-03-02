<?php

namespace App\Http\Controllers\Admin;

use App\Models\Recruitment;
use App\Models\Option;
use App\Models\Log as LogDB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecruitmentController extends Controller
{
    public $name           =   'Recruitment';
    public $log_model      =   'App\Models\Recruitment';
    public $back_from_list =   'recruitments.index';
    public $back_from_form =   'recruitments.index';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = \DB::select('SELECT 
            rec.id as id,
            dept.`name` as department_name, 
            job_position,
            number_of_people_requested,
            number_of_people_approved,
            req.`name` as user_requested,
            chg.`name` as user_changed,
            prt.`name` as priority,
            reqs.`name` as request_status,
            prcs.`name` as process_status,
            count(cand.id) as number_of_candidates,
            count(cand.id) > 0 as show_view_candidates
        FROM recruitments rec
        JOIN `options` dept ON rec.department_id = dept.id
        JOIN users req ON rec.requested_by_user	= req.id
        JOIN users chg ON rec.change_request_status_by_user = chg.id
        JOIN `options` prt ON rec.priority_id = prt.id
        JOIN `options` reqs ON rec.request_status_id = reqs.id
        JOIN `options` prcs ON rec.process_status_id = prcs.id
        LEFT JOIN candidates cand ON cand.recruitment_id = rec.id
        GROUP BY rec.id
        ORDER BY FIELD(prt.`name`,"HIGH", "NORMAL", "LOW")');
        // ->get();
        // dd($datas);
        $contents = array(
            array(
                'field'     =>  'department_name',
            ),
            array(
                'field'     =>  'job_position'
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
                'field'     =>  'user_requested',
                'label'     =>  'Requested by'
            ),
            array(
                'field'     =>  'user_changed',
                'label'     =>  'Approved/Rejected by'
            ),
            array(
                'field'     =>  'priority',
            ),
            array(
                'field'     =>  'request_status',
            ),
            array(
                'field'     =>  'process_status',
            ),
            array(
                'field'     =>  'number_of_candidates',
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
                    'label'                 =>  'edit',  // Button text to be shown in the HTML
                    'action'                =>  'recruitments.edit', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'warning',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','HR Manager','Management'], // Roles to be checked for the UI to be show
                ),
                array(
                    'label'                 =>  'approve',  // Button text to be shown in the HTML
                    'action'                =>  'recruitments.approve', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'success',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','Management'], // Roles to be checked for the UI to be show
                    'when'                  =>  'request_status', // Field or relation you want to check to show the button
                    'when_value'            =>  'WAITING FOR APPROVAL' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'adjustment',  // Button text to be shown in the HTML
                    'action'                =>  'recruitments.adjustment', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'warning',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','Management'], // Roles to be checked for the UI to be show
                    'when'                  =>  'request_status', // Field or relation you want to check to show the button
                    'when_value'            =>  'WAITING FOR APPROVAL' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'reject',   // Button text to be shown in the HTML
                    'action'                =>  'recruitments.reject', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'danger',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','Management'], // Roles to be checked for the UI to be show
                    'when'                  =>  'request_status', // Field or relation you want to check to show the button
                    'when_value'            =>  'WAITING FOR APPROVAL' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'start recruitment',   // Button text to be shown in the HTML
                    'action'                =>  'recruitments.start', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'success',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  array(
                        'request_status',
                        'process_status'
                    ), // Field or relation you want to check to show the button
                    'when_value'            =>  array(
                        'APPROVED',
                        'NOT YET PROCESSED'
                    ) // Value that right for the condition
                ),
                array(
                    'label'                 =>  'end recruitment',   // Button text to be shown in the HTML
                    'action'                =>  'recruitments.end', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'danger',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'process_status', // Field or relation you want to check to show the button
                    'when_value'            =>  'ON PROGRESS' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'add candidate',    // Button text to be shown in the HTML
                    'action'                =>  'candidates.create', // Routes to action, eg : dashboard.index, user.create
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'process_status', // Field or relation you want to check to show the button
                    'when_value'            =>  'ON PROGRESS' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'view candidates',  // Button text to be shown in the HTML
                    'roles'                 =>  ['Super Admin','HR Manager', 'Manager', 'Team Lead'], // Roles to be checked for the UI to be show
                    'action'                =>  'candidates.index', // Routes to action, eg : dashboard.index, user.create
                    'when'                  =>  array(
                        'process_status', 
                        'show_view_candidates'
                    ),// Field or relation you want to check to show the button
                    'when_value'            =>  array(
                        'ON PROGRESS',
                        '1'
                    ) // Value that right for the condition
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
                'field'     =>  'job_position',
                'type'      =>  'text'
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
                'job_position'                  =>  'required',
                'deadline'                      =>  'required',
                'priority_id'                   =>  'required',
                'request_status_id'             =>  'required',
                'requested_by_user'             =>  'required',
                'process_status_id'             =>  'required',
            ]
        );
        $data = $request->all();
        $data['job_position']                   =   strtoupper($request->job_position);
        $recruitment                            =   Recruitment::create($data);
        LogDB::create([
            'field'                             =>  'remark',
            'model'                             =>  $this->log_model,
            'model_id'  	                    =>  $recruitment->id,
            'value'                             =>  \Auth::user()->name.' : '.($request->remark ?? 'No remark').' \n On : '.\Carbon\Carbon::now().'\n\n'
        ]);

        return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Added Successfully");

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recruitment $model)
    {
        if ($request->number_of_people_approved) {
            // $request->validate(
            //     [
            //         'number_of_people_approved'     =>  'integer|min:0',
            //         'sallary_adjusted'              =>  'integer|min:0',
            //     ]
            // );
        }
        $data = $request->all();
        if (isset($request->sallary_adjusted) && is_null($request->sallary_adjusted)) {
            $data['sallary_adjusted'] = $data['sallary_proposed'];
        }
        $model->update($data);
        LogDB::create([
            'field'                             =>  'remark',
            'model'                             =>  $this->log_model,
            'model_id'  	                    =>  $model->id,
            'value'                             =>  \Auth::user()->name.' : '.($request->remark ?? 'No remark').' \n On : '.\Carbon\Carbon::now().'\n\n'
        ]);

        return redirect()->route($this->back_from_form)->withSuccess("$this->name has been Updated Successfully");
    }

    /**
     * Show the form for reject the recruitment.
     *
     * @param  \App\Models\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function edit(Recruitment $model)
    {
        $priorities         =   Option::where('type', 'PRIORITY')->pluck('name', 'id');
        $departments        =   Option::where('type', 'DEPARTMENT')->pluck('name', 'id');
        $logs               =   LogDB::where('model', $this->log_model)
        ->where('model_id',$model->id)
        ->orderBy('created_at', 'DESC')
        ->get();
        $contents   = array(
            array(
                'field'     =>  'department_id',
                'type'      =>  'select2',
                'data'      =>  $departments
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
                'field'     =>  'sallary_proposed',
                'type'      =>  'currency',
            ),
            array(
                'field'     =>  'priority_id',
                'type'      =>  'select2',
                'data'      =>  $priorities
            ),
        );
        return view('page.content.edit')
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Show the form for approve the recruitment.
     *
     * @param  \App\Models\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function approve(Recruitment $model)
    {
        $requestStatus      =   Option::firstWhere([
            'type'  =>  'REQUEST_STATUS',
            'name'  =>  'APPROVED'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->where('model_id',$model->id)
        ->orderBy('created_at', 'DESC')
        ->get();
        
        $contents           =   array(
            array(
                'field'     =>  'sallary_adjusted',
                'label'     =>  'Salary',
                'type'      =>  'currency',
                'state'     =>  'readonly',
                'value'     =>  $model->sallary_proposed
            ),
            array(
                'field'     =>  'number_of_people_approved',
                'label'     =>  'Number of people',
                'type'      =>  'number',
                'state'     =>  'readonly',
                'value'     =>  $model->number_of_people_requested,
            ),
            array(
                'label'     =>  'Remark for approval',
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'request_status_id',
                'type'      =>  'hidden',
                'value'     =>  $requestStatus
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
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Show the form for adjustment the recruitment.
     *
     * @param  \App\Models\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function adjustment(Recruitment $model)
    {
        $requestStatus      =   Option::firstWhere([
            'type'  =>  'REQUEST_STATUS',
            'name'  =>  'APPROVED'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->where('model_id',$model->id)
        ->orderBy('created_at', 'DESC')
        ->get();
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
                'has_logs'  =>  $logs->contains('field', 'remark'),
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
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Show the form for reject the recruitment.
     *
     * @param  \App\Models\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function reject(Recruitment $model)
    {
        $requestStatus      =   Option::firstWhere([
            'type'  =>  'REQUEST_STATUS',
            'name'  =>  'REJECTED'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->where('model_id',$model->id)
        ->orderBy('created_at', 'DESC')
        ->get();
        $contents   = array(
            array(
                'label'     =>  'Reason to reject',
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
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
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Show the form for reject the recruitment.
     *
     * @param  \App\Models\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function start(Recruitment $model)
    {
        $processStatus      =   Option::firstWhere([
            'type'  =>  'PROCESS_STATUS',
            'name'  =>  'ON PROGRESS'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->where('model_id',$model->id)
        ->orderBy('created_at', 'DESC')
        ->get();
        $contents   = array(
            array(
                'label'     =>  'Start date',
                'field'     =>  'start_process',
                'type'      =>  'date'
            ),
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'process_status_id',
                'type'      =>  'hidden',
                'value'     =>  $processStatus
            ),
            array(
                'field'     =>  'processed_by_user',
                'type'      =>  'hidden',
                'value'     =>  \Auth::user()->id
            ),
        );
        return view('page.content.edit')
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Show the form for reject the recruitment.
     *
     * @param  \App\Models\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function end(Recruitment $model)
    {
        $processStatus      =   Option::firstWhere([
            'type'  =>  'PROCESS_STATUS',
            'name'  =>  'DONE'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->where('model_id',$model->id)
        ->orderBy('created_at', 'DESC')
        ->get();
        $contents   = array(
            array(
                'label'     =>  'End date',
                'field'     =>  'end_process',
                'type'      =>  'date'
            ),
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'process_status_id',
                'type'      =>  'hidden',
                'value'     =>  $processStatus
            ),
            array(
                'field'     =>  'processed_by_user',
                'type'      =>  'hidden',
                'value'     =>  \Auth::user()->id
            ),
        );
        return view('page.content.edit')
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }
}
