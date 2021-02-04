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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Recruitment $model)
    {
        $datas = Candidate::with([
            'candidate_status',
        ])
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
                    'label'                 =>  'send offering',  // Button text to be shown in the HTML
                    'action'                =>  'recruitments.approve', // Routes to action, eg : dashboard.index, user.create
                    'class'                 =>  'success',  // Default button class, leave it blank if you want the primary color
                    'roles'                 =>  ['Super Admin','HR Manager'], // Roles to be checked for the UI to be show
                    'when'                  =>  'candidate_status', // Field or relation you want to check to show the button
                    'when_key'              =>  'name', // Only add this when we check on relationship value
                    'when_value'            =>  'WAITING FOR CONFIRMATION' // Value that right for the condition
                ),
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
    public function create(Recruitment $model)
    {
        $candidateStatus    =   Option::firstWhere([
            'type'  =>  'CANDIDATE_STATUS',
            'name'  =>  'WAITING FOR CONFIRMATION'
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
                'field'     =>  'test_result',
                'type'      =>  'text',
                'label'     =>  'Result test'
            ),
            array(
                'field'     =>  'interview_result',
                'type'      =>  'text',
                'label'     =>  'Result interview'
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Recruitment $model, Request $request)
    {
        $request->validate(
            [
                'name'                  =>  'required',
                'email'                 =>  'required',
                'phone'                 =>  'required',
                'expected_sallary'      =>  'required',
                'test_result'           =>  'required',
                'interview_result'      =>  'required',
                'curriculum_vitae'      =>  'required',
                'candidate_status_id'   =>  'required',
                'recruitment_id'        =>  'required',
            ]
        );
        $data = $request->all();
        if ($request->hasFile("curriculum_vitae")) {
            $fileName = Uuid::uuid4()->toString()."." . $request->file("curriculum_vitae")->getClientOriginalExtension();
            $request->file("curriculum_vitae")->move(public_path() . "uploads/cv/".$fileName);
            $data['curriculum_vitae'] = $fileName;
        }
        Candidate::create($data);
        return redirect()->route("candidates.view", $model->id)->withSuccess("Candidate has been Added Successfully");
    }
}
