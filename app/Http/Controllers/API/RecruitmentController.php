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
            DATE_FORMAT(rec.start_process, "%M %d, %Y") as start_date,
            DATE_FORMAT(rec.approved_datetime, "%M %d, %Y") as request_date,
            -- dept.`name` as department_name, 
            job_position,
            prt.`name` as priority,
            -- reqs.`name` as request_status,
            -- prcs.`name` as process_status,
            COUNT(DISTINCT candpr.id) as processed,
            COUNT(DISTINCT candpr_left.id) as processed_left,
            COUNT(DISTINCT cand_intv_left.id) as interviewed_left,
            COUNT(DISTINCT cand_intv.id) as interviewed,
            COUNT(DISTINCT candol_left.id) as ol_sent_left,
            COUNT(DISTINCT candol.id) as ol_sent,
            COUNT(DISTINCT candon.id) as on_board
        FROM recruitments rec
        JOIN `options` dept ON rec.department_id = dept.id
        JOIN `options` prt ON rec.priority_id = prt.id
        JOIN `options` reqs ON rec.request_status_id = reqs.id
        JOIN `options` prcs ON rec.process_status_id = prcs.id
        JOIN candidates candpr ON candpr.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                "WAITING FOR CONFIRMATION FROM USER",
                "CV SUITABLE",
                "FORM SCREENING SENT",
                "FORM SCREENING RECEIVED",
                "SUITABLE TO INTERVIEW"
            )
        ) candpr_left ON candpr_left.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                "SUITABLE FOR OL",
                "OFFERING LETTER SENT",
                "ON BOARDING"
            )
        ) cand_intv_left ON cand_intv_left.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                "WAITING FOR INTERVIEW WITH USER",
                "WAITING FOR USER\'S DECISION",
                "SUITABLE FOR OL",
                "OFFERING LETTER SENT",
                "ON BOARDING"
            )
        ) cand_intv ON cand_intv.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
                JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                    "OFFERING LETTER SENT"
                )
        ) candol_left ON candol_left.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
                JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                    "OFFERING LETTER SENT",
                    "ON BOARDING"
                )
        ) candol ON candol.recruitment_id = rec.id
        LEFT JOIN(
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            AND opt.`name` IN ("ON BOARDING")
        ) candon ON candon.recruitment_id = rec.id
        WHERE reqs.`name` IN ("ON PROGRESS", "APPROVED")
        GROUP BY rec.id
        ORDER BY FIELD(prt.`name`,"HIGH", "NORMAL", "LOW"), rec.created_at');
        return response()->json($datas, 200);
    }

    public function total_header()
    {
        $datas          =   
        \DB::select('SELECT
            COUNT(DISTINCT rec.id) as total_open_position,
            COUNT(DISTINCT candpr_left.id) as candidate_processed_today,
            COUNT(DISTINCT candpr.id) as candidate_processed,
            COUNT(DISTINCT cand_intv_left.id) as candidate_interview_today,
            COUNT(DISTINCT cand_intv.id) as candidate_interview,
            COUNT(DISTINCT candol_left.id) as number_of_ol_issued_today,
            COUNT(DISTINCT candol.id) as number_of_ol_issued,
            COUNT(DISTINCT candon.id) as number_of_on_board_today,
            COUNT(DISTINCT candon.id) as number_of_on_board
        FROM recruitments rec
        JOIN `options` proc_stat ON rec.process_status_id = proc_stat.id
        JOIN candidates candpr ON candpr.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                "WAITING FOR CONFIRMATION FROM USER",
                "CV SUITABLE",
                "FORM SCREENING SENT",
                "FORM SCREENING RECEIVED",
                "SUITABLE TO INTERVIEW"
            )
        ) candpr_left ON candpr_left.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                "SUITABLE FOR OL",
                "OFFERING LETTER SENT",
                "ON BOARDING"
            )
        ) cand_intv_left ON cand_intv_left.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                "WAITING FOR INTERVIEW WITH USER",
                "WAITING FOR USER\'S DECISION",
                "SUITABLE FOR OL",
                "OFFERING LETTER SENT",
                "ON BOARDING"
            )
        ) cand_intv ON cand_intv.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
                JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                    "OFFERING LETTER SENT"
                )
        ) candol_left ON candol_left.recruitment_id = rec.id
        LEFT JOIN (
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
                JOIN candidate_status_logs csl ON cand.id = csl.candidate_id AND csl.candidate_status_id = opt.id
            AND opt.`name` IN (
                    "OFFERING LETTER SENT",
                    "ON BOARDING"
                )
        ) candol ON candol.recruitment_id = rec.id
        LEFT JOIN(
            SELECT 
                cand.id as id, 
                cand.recruitment_id 
            FROM candidates cand
            JOIN `options` opt ON cand.candidate_status_id = opt.id
            AND opt.`name` IN ("ON BOARDING")
        ) candon ON candon.recruitment_id = rec.id
        WHERE proc_stat.`name` IN (
            "ON PROGRESS",
            "APPROVED"
        )');
        return response()->json($datas, 200);
    }
}
