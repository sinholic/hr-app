<?php

use Illuminate\Database\Seeder;
use App\Models\Option;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $options = array(
            array(
                'type'  =>  'DEPARTMENT',
                'name'  =>  'TECH'
            ),
            array(
                'type'  =>  'DEPARTMENT',
                'name'  =>  'MARKETING'
            ),
            array(
                'type'  =>  'DEPARTMENT',
                'name'  =>  'SALES'
            ),
            array(
                'type'  =>  'DEPARTMENT',
                'name'  =>  'OPERATION'
            ),
            array(
                'type'  =>  'DEPARTMENT',
                'name'  =>  'HUMAN RESOURCE'
            ),
            array(
                'type'  =>  'PRIORITY',
                'name'  =>  'LOW'
            ),
            array(
                'type'  =>  'PRIORITY',
                'name'  =>  'NORMAL'
            ),
            array(
                'type'  =>  'PRIORITY',
                'name'  =>  'HIGH'
            ),
            array(
                'type'  =>  'REQUEST_STATUS',
                'name'  =>  'WAITING FOR APPROVAL'
            ),
            array(
                'type'  =>  'REQUEST_STATUS',
                'name'  =>  'APPROVED'
            ),
            array(
                'type'  =>  'REQUEST_STATUS',
                'name'  =>  'REJECTED'
            ),
            array(
                'type'  =>  'PROCESS_STATUS',
                'name'  =>  'NOT YET PROCESSED'
            ),
            array(
                'type'  =>  'PROCESS_STATUS',
                'name'  =>  'ON PROGRESS'
            ),
            array(
                'type'  =>  'PROCESS_STATUS',
                'name'  =>  'DONE'
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "NOT SUITABLE TO INTERVIEW"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "OFFERING LETTER SENT"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "CV NOT SUITABLE"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "WAITING FOR USER'S DECISION"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "SUITABLE FOR OL"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "FORM SCREENING RECEIVED"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "SUITABLE"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "WAITING FOR CONFIRMATION FROM CANDIDATE"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "CV SUITABLE"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "WAITING FOR INTERVIEW WITH USER"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "NOT SUITABLE FOR OL"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "WAITING FOR CONFIRMATION FROM USER"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "NOT SUITABLE"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "SUITABLE TO INTERVIEW"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "CANCELED"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "ON BOARDING"
            ),
            array(
                'type'  =>  'CANDIDATE_STATUS',
                'name'  =>  "FORM SCREENING SENT"
            ),
        );

        foreach ($options as $key => $option) {
            Option::create($option);
        }
    }
}
