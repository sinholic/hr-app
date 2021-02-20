<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Recruitment;

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
    public function humanResource()
    {
        // - Dept
        // - Requestor
        // - Approved by
        // - Action by
        // - Proposed
        // - Approved
        // - Actual
        $datas = Recruitment::with([
            'department',
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
                'label'     =>  'Approved/Rejected by'
            ),
            array(
                'field'     =>  'user_processed',
                'key'       =>  'name',
                'label'     =>  'Action by'
            ),
            array(
                'field'     =>  'number_of_people_requested',
                'label'     =>  'Proposed'
            ),
            array(
                'field'     =>  'number_of_people_approved',
                'label'     =>  'Approved'
            ),
            array(
                'field'     =>  'candidates',
                'label'     =>  'Actual',
                'type'      =>  'count'
            ),
        );
        $view_options = array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
        );
        return view('page.content.index')
        ->with('datas', $datas)
        ->with('contents', $contents)
        ->with('view_options', $view_options);
    }
}