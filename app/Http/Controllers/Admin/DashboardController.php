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
        $departments        =   '"'.implode('","', $departments->toArray()).'"';
        $where              =   "AND 1 = 1";
        if ($request->created_at != '' && $request->created_at != 'All') {
            $created_at     =   explode("-",$request->created_at);
            // $datas          =   $datas->whereYear('created_at', $created_at[0])
            // ->whereMonth('created_at', $created_at[1]);
            $where          =   "AND YEAR(rec.created_at) = $created_at[0] AND MONTH(rec.created_at) = $created_at[1]";
        }
        $datas              =   \DB::select('SELECT 
            DATE_FORMAT(rec.created_at, "%M %d, %Y") as created_at,
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
            COUNT(DISTINCT candfs.id) as number_of_form_sent,
            COUNT(DISTINCT candfr.id) as number_of_form_returned,
            COUNT(DISTINCT candol.id) as number_of_ol_issued,
            COUNT(DISTINCT candon.id) as number_of_on_board
        FROM recruitments rec
        JOIN `options` dept ON rec.department_id = dept.id
        JOIN users req ON rec.requested_by_user	= req.id
        JOIN users chg ON rec.change_request_status_by_user = chg.id
        JOIN `options` prt ON rec.priority_id = prt.id
        JOIN `options` reqs ON rec.request_status_id = reqs.id
        JOIN `options` prcs ON rec.process_status_id = prcs.id
        LEFT JOIN (
            SELECT cand.id, cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id AND opt.`name` IN ("FORM SCREENING SENT")
        ) candfs ON candfs.recruitment_id = rec.id
        LEFT JOIN (
            SELECT cand.id, cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id AND opt.`name` IN ("FORM SCREENING RECEIVED")
        ) candfr ON candfr.recruitment_id = rec.id
        LEFT JOIN (
            SELECT cand.id, cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id AND opt.`name` IN ("OFFERING LETTER SENT")
        ) candol ON candol.recruitment_id = rec.id
        LEFT JOIN (
            SELECT cand.id, cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id AND opt.`name` IN ("ON BOARDING")
        ) candon ON candon.recruitment_id = rec.id
        WHERE rec.department_id IN ('.$departments.')
        '.$where.'
        GROUP BY rec.id
        ORDER BY FIELD(prt.`name`,"HIGH", "NORMAL", "LOW"), rec.created_at DESC', 
        ['departments' => $departments]);

        $contents           =   array(
            array(
                'field'     =>  'created_at',
                'label'     =>  'Requested at',
            ),
            array(
                'field'     =>  'department_name',
            ),
            array(
                'field'     =>  'job_position'
            ),
            array(
                'field'     =>  'priority'
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
                'field'     =>  'number_of_form_sent',
                'label'     =>  '# Form Sent',
            ),
            array(
                'field'     =>  'number_of_form_returned',
                'label'     =>  '# Form Returned',
            ),
            array(
                'field'     =>  'number_of_ol_issued',
                'label'     =>  '# OL Issued',
            ),
            array(
                'field'     =>  'number_of_on_board',
                'label'     =>  '# On Boarding',
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