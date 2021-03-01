<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use App\Models\Option;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('page.dashboard.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function humanResource(Request $request)
    {
        $departments        =   Option::where('type', 'DEPARTMENT')->pluck('id');
        $roles              =   \Auth::user()->getRoleNames()->toArray();
        if (in_array('Manager', $roles)||
            in_array('Team Lead', $roles)||
            in_array('Employee', $roles)) {
            $departments    =   [\Auth::user()->department_id];
        }
        $datas              =   Recruitment::with([
            'department',
            'user_requested',
            'user_change_status',
            'user_processed',
            'priority',
            'request_status',
            'process_status',
            'candidates',
            'candidates.candidate_status'
        ])
        ->whereIn('department_id', $departments);
        if ($request->created_at != '' && $request->created_at != 'All') {
            $created_at     =   explode("-",$request->created_at);
            $datas          =   $datas->whereYear('created_at', $created_at[0])
            ->whereMonth('created_at', $created_at[1]);
        }
        $datas              =   $datas->orderBy('created_at','DESC')
        ->get();

        $contents           =   array(
            array(
                'field'     =>  'created_at',
                'label'     =>  'Requested at',
                'type'      =>  'date',
                'format'    =>  'F j, Y'
            ),
            array(
                'field'     =>  'department',
                'key'       =>  'name'
            ),
            array(
                'field'     =>  'job_position'
            ),
            array(
                'field'     =>  'user_requested',
                'key'       =>  'name',
                'label'     =>  'Requested by'
            ),
            array(
                'field'     =>  'user_change_status',
                'key'       =>  'name',
                'label'     =>  'Approved / Rejected by'
            ),
            array(
                'field'     =>  'user_processed',
                'key'       =>  'name',
                'label'     =>  'Action by'
            ),
            array(
                'field'     =>  'number_of_people_requested',
                'label'     =>  '# Proposed'
            ),
            array(
                'field'     =>  'number_of_people_approved',
                'label'     =>  '# Approved'
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
                'label'     =>  '# Form Sent',
                'type'      =>  'rel_where_count',
                'rel'       =>  'candidate_status',
                'rel_key'   =>  'name',
                'rel_val'   =>  [
                    'FORM SCREENING SENT',
                ]
            ),
            array(
                'field'     =>  'candidates',
                'label'     =>  '# Form Returned',
                'type'      =>  'rel_where_count',
                'rel'       =>  'candidate_status',
                'rel_key'   =>  'name',
                'rel_val'   =>  [
                    'FORM SCREENING SENT',
                    'FORM SCREENING RECEIVED',
                ]
            ),
            array(
                'field'     =>  'candidates',
                'label'     =>  '# OL Issued',
                'type'      =>  'rel_where_count',
                'rel'       =>  'candidate_status',
                'rel_key'   =>  'name',
                'rel_val'   =>  [
                    'OFFERING LETTER SENT',
                ]
            ),
            array(
                'field'     =>  'candidates',
                'label'     =>  '# On Boarding',
                'type'      =>  'rel_where_count',
                'rel'       =>  'candidate_status',
                'rel_key'   =>  'name',
                'rel_val'   =>  [
                    'ON BOARDING',
                ]
            ),
        );
        $view_options       =   array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
        );
        $filters            =   array(
            array(
                'field'     =>  'created_at',
                'label'     =>  'Requested at',
                'type'      =>  'filter_month_year',
                'value'     =>  $request->created_at
            )
        );
        return view('page.content.index')
        ->with('filters', $filters) 
        ->with('datas', $datas)
        ->with('contents', $contents)
        ->with('view_options', $view_options);
    }
}