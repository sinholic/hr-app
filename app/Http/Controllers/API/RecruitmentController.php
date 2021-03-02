<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recruitment;
use App\Models\Option;
use App\Models\Log as LogDB;

class RecruitmentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function open()
    {
        $datas          =   \DB::select('SELECT 
            DATE_FORMAT(rec.start_process, "%M %d, %Y") as start_process,
            dept.`name` as department_name, 
            job_position,
            prt.`name` as priority,
            reqs.`name` as request_status,
            prcs.`name` as process_status,
            COUNT(DISTINCT candpr.id) as number_of_proceed,
            COUNT(DISTINCT candol.id) as number_of_ol_issued,
            COUNT(DISTINCT candon.id) as number_of_on_board
        FROM recruitments rec
        JOIN `options` dept ON rec.department_id = dept.id
        JOIN `options` prt ON rec.priority_id = prt.id
        JOIN `options` reqs ON rec.request_status_id = reqs.id
        JOIN `options` prcs ON rec.process_status_id = prcs.id
        LEFT JOIN (
            SELECT cand.id as id, cand.recruitment_id FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id 
                        AND opt.`name` IN ("OFFERING LETTER SENT")
        ) candol ON candol.recruitment_id = rec.id
        LEFT JOIN(
            SELECT cand.id as id, cand.recruitment_id FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id 
                        AND opt.`name` IN ("ON BOARDING")
        ) candon ON candon.recruitment_id = rec.id
                LEFT JOIN (
            SELECT cand.id as id, cand.recruitment_id FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
                        AND opt.`name` IN (
                            "CV SUITABLE",
                            "FORM SCREENING SENT",
                            "FORM SCREENING RECEIVED",
                            "SUITABLE TO INTERVIEW",
                            "WAITING FOR INTERVIEW WITH USER",
                            "WAITING FOR INTERVIEW WITH USER",
                            "WAITING FOR USER\'S DECISION"
                        )
        ) candpr ON candpr.recruitment_id = rec.id
                WHERE reqs.`name` NOT IN ("DONE", "REJECTED")
        GROUP BY rec.id
        ORDER BY FIELD(prt.`name`,"HIGH", "NORMAL", "LOW")');

        return response()->json($datas, 200);
    }
}
