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
     * @param  \App\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function index(Recruitment $model)
    {
        $datas = Candidate::with([
            'candidate_status',
        ])
        ->where('recruitment_id', $model->id)
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
                'field'     =>  'expected_sallary',
                'label'     =>  'Expected sallary'
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
                'field'     =>  'candidate_status',
                'key'       =>  'name'
            ),
            array(
                'field'     =>  'curriculum_vitae',
                'label'     =>  'View or Download CV',
                'type'      =>  'link'
            ),
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
            'enable_add'                =>  array(
                'state'                 =>  true,
                'action'                =>  'candidates.create',
                'params'                =>  $model->id
            ),
            'enable_delete'             =>  false,
            'enable_edit'               =>  false,
            'enable_action'             =>  true,
            'button_extends'            =>  array(
                array(
                    'label'                 =>  'edit',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.edit', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model->id],
                    'class'                 =>  'warning',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM CANDIDATE' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'schedule interview',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.schedule_interview', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model->id],
                    'class'                 =>  'success',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM CANDIDATE' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'add result',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.result', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model->id],
                    'class'                 =>  'primary',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR INTERVIEW WITH USER' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'send offering',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.result', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model->id],
                    'class'                 =>  'primary',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM USER' // Value that right for the condition
                ),
                array(
                    'label'                 =>  'not suitable',  // Button text to be shown in the HTML
                    'action'                =>  'candidates.result', // Routes to action, eg : dashboard.index, user.create
                    'params'                =>  ['model_url'   =>  $model->id],
                    'class'                 =>  'danger',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION FROM USER' // Value that right for the condition
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
     * @param  \App\Recruitment  $model
     * @return \Illuminate\Http\Response
     */
    public function create(Recruitment $model)
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
                'field'     =>  'expected_sallary',
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
                'value'     =>  $model->id
            )
        );
        return view('page.content.add')
        ->with('contents', $contents);
    }

    /**
     * Store a newly candidate for recruitment in storage.
     * 
     * @param  \App\Recruitment  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Recruitment $model, Request $request)
    {
        $request->validate(
            [
                'name'                  =>  'required',
                'email'                 =>  'required|email',
                'phone'                 =>  'required|integer',
                'expected_sallary'      =>  'required|integer',
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
        return redirect()->route("candidates.view", $model->id)->withSuccess("Candidate has been Added Successfully");
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
        }elseif ($request->interview_result) {
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
        

        return redirect()->route("candidates.view", $model_url->id)->withSuccess("Candidate has been Updated Successfully");
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
                'field'     =>  'expected_sallary',
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
    public function schedule_interview(Recruitment $model_url, Candidate $model, Request $request)
    {
        $candidateStatus      =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'WAITING FOR INTERVIEW WITH USER'
        ])->id;
        $contents   = array(
            array(
                'field'     =>  'interview_date',
                'type'      =>  'date'
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
}
