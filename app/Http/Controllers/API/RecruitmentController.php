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
        $datas          =   
        \DB::select('SELECT 
            DATE_FORMAT(rec.start_process, "%M %d, %Y") as start_process,
            dept.`name` as department_name, 
            job_position,
            prt.`name` as priority,
            reqs.`name` as request_status,
            prcs.`name` as process_status,
            COUNT(DISTINCT candpr.id) as number_of_proceed,
            COUNT(DISTINCT candpr_today.id) as number_of_proceed_today,
            COUNT(DISTINCT cand_intv_today.id) as number_of_interview_today,
            COUNT(DISTINCT candol.id) as number_of_ol_issued,
            COUNT(DISTINCT candol_today.id) as number_of_ol_issued_today,
            COUNT(DISTINCT candon.id) as number_of_on_board,
            COUNT(DISTINCT candon_today.id) as number_of_on_board_today
        FROM recruitments rec
        JOIN `options` dept ON rec.department_id = dept.id
        JOIN `options` prt ON rec.priority_id = prt.id
        JOIN `options` reqs ON rec.request_status_id = reqs.id
        JOIN `options` prcs ON rec.process_status_id = prcs.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
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
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                    "CV SUITABLE",
                    "FORM SCREENING SENT",
                    "FORM SCREENING RECEIVED",
                    "SUITABLE TO INTERVIEW",
                    "WAITING FOR INTERVIEW WITH USER",
                    "WAITING FOR USER\'S DECISION"
            )
            WHERE DATE(csl.action_datetime) = DATE(NOW())
        ) candpr_today ON candpr_today.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            AND opt.`name` IN (
                "WAITING FOR INTERVIEW WITH USER",
                "WAITING FOR USER\'S DECISION"
            )
            WHERE DATE(cand.interview_date) = DATE(NOW())
        ) cand_intv_today ON cand_intv_today.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id 
            AND opt.`name` IN ("OFFERING LETTER SENT")
        ) candol ON candol.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN ("OFFERING LETTER SENT")
            WHERE DATE(csl.action_datetime) = DATE(NOW())
        ) candol_today ON candol_today.recruitment_id = rec.id
        LEFT JOIN(
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id 
            AND opt.`name` IN ("ON BOARDING")
        ) candon ON candon.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            AND opt.`name` IN (
                "ON BOARDING"
            )
            WHERE DATE(cand.joindate) = DATE(NOW())
        ) candon_today ON candon_today.recruitment_id = rec.id
        WHERE reqs.`name` NOT IN ("DONE", "REJECTED")
        GROUP BY rec.id
        ORDER BY FIELD(prt.`name`,"HIGH", "NORMAL", "LOW")');
        return response()->json($datas, 200);
    }
}
