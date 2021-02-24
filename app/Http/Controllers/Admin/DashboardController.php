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
        $current_year = date('Y');
        $months = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        $month_names = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

        // Loop through all the months and create an array up to and including the current month

        for ($month=1;$month<=date('m');$month++)
        {
            $years[$current_year][$current_year.'-'.$month] = $month_names[$month-1];
        }

        // Previous years
        // $years_to_create = $current_year - ($current_year);
        // if (!empty($years_to_create))
        // {
        //     for ($i = 1; $i <= $years_to_create; $i++)
        //     {
        //         $years[$current_year - $i] = $month_names;
        //     }
        // }

        // dd($years);
        $departments        =   Option::where('type', 'DEPARTMENT')->pluck('id');
        $roles              =   \Auth::user()->getRoleNames()->toArray();
        if (in_array('Manager', $roles)) {
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
        ])
        ->whereIn('department_id', $departments);
        if ($request->created_at) {
            $created_at     =   explode("-",$request->created_at);
            $datas          =   $datas->whereYear('created_at', $created_at[0])
            ->whereMonth('created_at', $created_at[1]);
        }
        $datas              =   $datas->orderBy('created_at','DESC')
        ->get();
        // dd($datas);
        $contents           =   array(
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
        $view_options       =   array(
            'table_class_override'      =>  'table-bordered table-striped table-responsive-stack',
        );
        $filters            =   array(
            array(
                'field'     =>  'created_at',
                'label'     =>  'Requested at',
                'type'      =>  'select2',
                'data'      =>  $years,
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