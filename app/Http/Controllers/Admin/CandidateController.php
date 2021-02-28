<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Recruitment;
use App\Models\Option;
use App\Models\Log as LogDB;
use Ramsey\Uuid\Uuid;

class CandidateController extends Controller
{
    public $name           =   'Candidate';
    public $log_model      =   'App\Models\Candidate';
    public $back_from_list =   'recruitments.index';
    public $back_from_form =   'candidates.index';
    /**
     * Display a listing of the candidate from recruitment id.
     * 
     * @param  \App\Recruitment  $model_url
     * @return \Illuminate\Http\Response
     */
    public function index(Recruitment $model_url)
    {
        $datas = Candidate::with([
            'candidate_status',
        ])
        ->where('recruitment_id', $model_url->id)
        ->orderBy('created_at','DESC')
        ->get();
        $contents = array(
            array(
                'field'     =>  'name',
            ),
            array(
                'field'     =>  'email',
                'label'     =>  'E-Mail'
            ),
            array(
                'field'     =>  'expected_salary',
                'label'     =>  'Expected salary'
            ),
            array(
                'field'     =>  'test_result',
                'label'     =>  'Result test'
            ),
            array(
                'field'     =>  'interview_result',
                'label'     =>  'Result interview'
            ),
            array(
                'field'     =>  'interview_date',
                // 'label'     =>  'Join date',
                // 'type'      =>  'date',
                // 'format'    =>  'F j, Y, g:i a'
            ),
            array(
                'field'     =>  'joindate',
                'label'     =>  'Join date',
                // 'type'      =>  'date',
                // 'format'    =>  'F j, Y'
            ),
            array(
                'field'     =>  'candidate_status',
                'key'       =>  'name'
            ),
            array(
                'field'     =>  'curriculum_vitae',
                'label'     =>  'Download CV',
                'type'      =>  'download'
            ),
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
            'enable_add'                =>  array(
                'state'                 =>  true,
                'action'                =>  'candidates.create',
                'params'                =>  $model_url->id,
                'roles'                 =>  ['Super Admin','HR Manager'],
            ),
            'enable_delete'             =>  false,
            'enable_edit'               =>  false,
            'enable_action'             =>  true,
            'button_extends'            =>  array(
                // Edit
                array(
                    'label'                 =>  'edit',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.edit', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'warning',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','HR Manager','Management'], // Roles to be checked for the UI to be show
                    'hide_when'             =>  'candidate_status', // Field or relation you want to check to show the button
                    'hide_when_key'         =>  'name', // Only add this when we check on relationship value
                    'hide_when_value'       =>  'ON BOARDING' // Value that right for the condition
                ),
                // Cancel Join
                array(
                    'label'                 =>  'cancel join',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.cancel_join', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'danger',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'hide_when'             =>  'candidate_status', // Field or relation you want to check to show the button
                    'hide_when_key'         =>  'name', // Only add this when we check on relationship value
                    'hide_when_value'       =>  'ON BOARDING' // Value that right for the condition
                ),
                // CV Suitable
                array(
                    'label'                 =>  'CV suitable',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.cv_suitable', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'primary',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM USER' // Value that right for the condition
                ),
                // CV Not Suitable
                array(
                    'label'                 =>  'CV not suitable',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.cv_not_suitable', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'danger',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','Manager','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM USER' // Value that right for the condition
                ),
                // Send Screening Form
                array(
                    'label'                 =>  'send screening form',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.send_screening_form', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'primary',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'CV SUITABLE' // Value that right for the condition
                ),
                // Receive Screening Form
                array(
                    'label'                 =>  'receive screening form',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.receive_screening_form', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'primary',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'FORM SCREENING SENT' // Value that right for the condition
                ),
                // Suitable to interview
                array(
                    'label'                 =>  'suitable to interview',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.suitable_to_interview', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'success',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','Manager', 'HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'FORM SCREENING RECEIVED' // Value that right for the condition
                ),
                // Not Suitable to interview
                array(
                    'label'                 =>  'not suitable to interview',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.not_suitable_to_interview', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'danger',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','Manager','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'FORM SCREENING RECEIVED' // Value that right for the condition
                ),
                // Schedule Interview
                array(
                    'label'                 =>  'schedule interview',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.schedule', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'success',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'SUITABLE TO INTERVIEW' // Value that right for the condition
                ),
                // Add Result test and interview
                array(
                    'label'                 =>  'add result',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.result', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'primary',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR INTERVIEW WITH USER' // Value that right for the condition
                ),
                // Send Offer
                array(
                    'label'                 =>  'send offering',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.send_offering', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'primary',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  "WAITING FOR USER'S DECISION" // Value that right for the condition
                ),
                // Approve Join
                array(
                    'label'                 =>  'approve join',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.approve_join', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'success',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'OFFERING LETTER SENT' // Value that right for the condition
                ),
            )
        );
        return view('page.content.index')
        ->with('datas', $datas)
        ->with('contents', $contents)
        ->with('view_options', $view_options);
    }

    /**
     * Show the form for add a new candidate for recruitment id.
     * 
     * @param  \App\Recruitment  $model_url
     * @return \Illuminate\Http\Response
     */
    public function create(Recruitment $model_url)
    {
        $candidateStatus    =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'WAITING FOR CONFIRMATION FROM USER'
        ])->id;
        $contents   = array(
            array(
                'field'     =>  'name',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'email',
                'type'      =>  'text',
                'label'     =>  'E-Mail'
            ),
            array(
                'field'     =>  'phone',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'expected_salary',
                'type'      =>  'currency',
            ),
            array(
                'field'     =>  'curriculum_vitae',
                'type'      =>  'file'
            ),            
            array(
                'field'     =>  'remark',
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
            array(
                'field'     =>  'recruitment_id',
                'type'      =>  'hidden',
                'value'     =>  $model_url->id
            )
        );
        return view('page.content.add')
        ->with('contents', $contents);
    }

    /**
     * Store a newly candidate for recruitment in storage.
     * 
     * @param  \App\Recruitment  $model_url
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Recruitment $model_url, Request $request)
    {
        $request->validate(
            [
                'name'                  =>  'required',
                'email'                 =>  'required|email',
                'phone'                 =>  'required',
                'candidate_status_id'   =>  'required',
                'recruitment_id'        =>  'required',
            ]
        );
        $data                           = $request->all();
        if ($request->hasFile("curriculum_vitae")) {
            $fileName = Uuid::uuid4()->toString()."." . $request->file("curriculum_vitae")->getClientOriginalExtension();
            $request->file("curriculum_vitae")->move(public_path() . "/storage/uploads/cv/", $fileName);
            $data['curriculum_vitae']   = $fileName;
        }
        $candidate                      =   Candidate::create($data);
        LogDB::create([
            'field'                     =>  'remark',
            'model'                     =>  $this->log_model,
            'model_id'  	            =>  $candidate->id,
            'value'                     =>  \Auth::user()->name.' : '.($request->remark ?? 'No remark').' \n On : '.\Carbon\Carbon::now().'\n\n'
        ]);
        return redirect()->route($this->back_from_form, $model_url->id)->withSuccess("$this->name has been Added Successfully");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request     
     * @return \Illuminate\Http\Response
     */
    public function update(Recruitment $model_url, Candidate $model, Request $request)
    {
        $data = $request->all();
        if ($request->interview_date) {
            $request->validate(
                [
                    'interview_date'        =>  'required',
                ]
            );
        }
        if ($request->interview_result || $request->test_result || $request->fileresult) {
            $request->validate(
                [
                    'interview_result'      =>  'required',
                    'test_result'           =>  'required',
                    'fileresult'            =>  'required',
                ]
            );
        }
        if ($request->hasFile("curriculum_vitae")) {
            $fileName = Uuid::uuid4()->toString()."." . $request->file("curriculum_vitae")->getClientOriginalExtension();
            $request->file("curriculum_vitae")->move(public_path() . "/storage/uploads/cv/", $fileName);
            $data['curriculum_vitae'] = $fileName;
        }
        if ($request->hasFile("fileresult")) {
            $fileName = Uuid::uuid4()->toString()."." . $request->file("fileresult")->getClientOriginalExtension();
            $request->file("fileresult")->move(public_path() . "/storage/uploads/result/", $fileName);
            $data['fileresult'] = $fileName;
        }
        $model->update($data);
        LogDB::create([
            'field'                     =>  'remark',
            'model'                     =>  $this->log_model,
            'model_id'  	            =>  $model->id,
            'value'                     =>  \Auth::user()->name.' : '.($request->remark ?? 'No remark').' \n On : '.\Carbon\Carbon::now().'\n\n'
        ]);

        return redirect()->route($this->back_from_form, $model_url->id)->withSuccess("$this->name has been Updated Successfully");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Recruitment $model_url, Candidate $model, Request $request)
    {
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'name',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'email',
                'type'      =>  'text',
                'label'     =>  'E-Mail'
            ),
            array(
                'field'     =>  'phone',
                'type'      =>  'text',
            ),
            array(
                'field'     =>  'expected_salary',
                'type'      =>  'currency',
            ),
            array(
                'field'     =>  'curriculum_vitae',
                'type'      =>  'file',
                'value'     =>  $model->curriculum_vitae
            ),            
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cv_suitable(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'CV SUITABLE'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cv_not_suitable(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'CV NOT SUITABLE'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_screening_form(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'FORM SCREENING SENT'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function receive_screening_form(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'FORM SCREENING RECEIVED'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function suitable_to_interview(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'SUITABLE TO INTERVIEW'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function not_suitable_to_interview(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'NOT SUITABLE TO INTERVIEW'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function schedule(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'WAITING FOR INTERVIEW WITH USER'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'interview_date',
                'type'      =>  'datetime'
            ),
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function result(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus    =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  "WAITING FOR USER'S DECISION"
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'interview_result',
                'type'      =>  'text'
            ),
            array(
                'field'     =>  'test_result',
                'type'      =>  'text'
            ),
            array(
                'field'     =>  'fileresult',
                'label'     =>  'File result',
                'type'      =>  'file'
            ),
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send_offering(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'OFFERING LETTER SENT'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'name',
                'type'      =>  'text',
                'state'     =>  'readonly'
            ),
            array(
                'field'     =>  'address',
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'phone',
                'type'      =>  'text',
                'state'     =>  'readonly'
            ),
            array(
                'field'     =>  'proposed_salary',
                'type'      =>  'currency',
            ),
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approve_join(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'ON BOARDING'
        ])->id;
        $logs               =   LogDB::where('model', $this->log_model)
        ->orderBy('created_at', 'DESC')
        ->where('model_id',$model->id)
        ->get();
        $contents   = array(
            array(
                'field'     =>  'joindate',
                'type'      =>  'date',
                'label'     =>  'Join date'
            ),
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Recruitment  $model_url
     * @param  \App\Candidate  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancel_join(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'CANCELED'
        ])->id;
        $contents   = array(
            array(
                'field'     =>  'remark',
                'has_logs'  =>  $logs->contains('field', 'remark'),
                'type'      =>  'textarea'
            ),
            array(
                'field'     =>  'candidate_status_id',
                'type'      =>  'hidden',
                'value'     =>  $candidateStatus
            ),
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
        ->with('logs',$logs)
        ->with('contents', $contents);
    }
}
