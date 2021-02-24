<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Recruitment;
use App\Models\Option;
use Ramsey\Uuid\Uuid;

class CandidateController extends Controller
{
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
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM CANDIDATE' // Value that right for the condition
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
                    'when_value'            =>  'SUITABLE' // Value that right for the condition
                ),
                // Suitable
                array(
                    'label'                 =>  'suitable',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.suitable', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'primary',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM CANDIDATE' // Value that right for the condition
                ),
                // Not Suitable
                array(
                    'label'                 =>  'not suitable',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.not_suitable', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'danger',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM CANDIDATE' // Value that right for the condition
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
                // Not Suitable
                array(
                    'label'                 =>  'not suitable',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.not_suitable', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'danger',  // Default button class, eg: primary, success, warning, danger, info
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM USER' // Value that right for the condition
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
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM USER' // Value that right for the condition
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
                // Cancel Join
                array(
                    'label'                 =>  'cancel join',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.cancel_join', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model_url->id],
                    'class'                 =>  'danger',  // Default button class, eg: primary, success, warning, danger, info
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
            'name'  =>  'WAITING FOR CONFIRMATION FROM CANDIDATE'
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
                'expected_salary'       =>  'required',
                'curriculum_vitae'      =>  'required',
                'candidate_status_id'   =>  'required',
                'recruitment_id'        =>  'required',
            ]
        );
        $data = $request->all();
        if ($request->hasFile("curriculum_vitae")) {
            $fileName = Uuid::uuid4()->toString()."." . $request->file("curriculum_vitae")->getClientOriginalExtension();
            $request->file("curriculum_vitae")->move(public_path() . "/storage/uploads/cv/", $fileName);
            $data['curriculum_vitae'] = $fileName;
        }
        Candidate::create($data);
        return redirect()->route("candidates.index", $model_url->id)->withSuccess("Candidate has been Added Successfully");
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
        }elseif ($request->interview_result || $request->test_result) {
            $request->validate(
                [
                    'interview_result'      =>  'required',
                    'test_result'           =>  'required'
                ]
            );
        }elseif ($request->hasFile("curriculum_vitae")) {
            $fileName = Uuid::uuid4()->toString()."." . $request->file("curriculum_vitae")->getClientOriginalExtension();
            $request->file("curriculum_vitae")->move(public_path() . "/storage/uploads/cv/", $fileName);
            $data['curriculum_vitae'] = $fileName;
        }
        $model->update($data);
        

        return redirect()->route("candidates.index", $model_url->id)->withSuccess("Candidate has been Updated Successfully");
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
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
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
        $contents   = array(
            array(
                'field'     =>  'interview_date',
                'type'      =>  'datetime'
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
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
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
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'WAITING FOR CONFIRMATION FROM USER'
        ])->id;
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
                'field'     =>  'remark',
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
    public function suitable(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'SUITABLE'
        ])->id;
        $contents   = array(
            array(
                'field'     =>  'remark',
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
    public function not_suitable(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'NOT SUITABLE'
        ])->id;
        $contents   = array(
            array(
                'field'     =>  'remark',
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
        $contents   = array(
            array(
                'field'     =>  'joindate',
                'type'      =>  'date',
                'label'     =>  'Join date'
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
        );
        return view('page.content.edit')
        ->with('model_url', $model_url)
        ->with('model', $model)
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
        ->with('contents', $contents);
    }
}
