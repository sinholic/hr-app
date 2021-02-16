<?php

use Illuminate\Database\Seeder;
use App\Models\Candidate;
use App\Models\Option;
use App\Models\User;
use App\Models\Recruitment;

class RecruitmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users              =   User::all();
        $departments        =   Option::where('type', 'DEPARTMENT')->get();
        $priorities         =   Option::where('type', 'PRIORITY')->get();
        $requestStatus      =   Option::firstWhere([
            'type'  =>  'REQUEST_STATUS',
            'name'  =>  'WAITING FOR APPROVAL'
        ])->id;
        $processStatus      =   Option::firstWhere([
            'type'  =>  'PROCESS_STATUS',
            'name'  =>  'NOT YET PROCESSED'
        ])->id;
        $data = [
            'department_id' => $departments[0]->id,
            'job_position' => 'Example',
            'number_of_people_requested' => 10,
            'requirements' => 'DevOps engineers build, test and maintain the infrastructure and tools to allow for the speedy development and release of software. DevOps practices aim to simplify the development process of software.',
            'deadline' => '2021-05-01',
            'sallary_proposed' => '30000000',
            'priority_id' => $priorities[0]->id,
            'request_status_id' => $requestStatus,
            'requested_by_user' => $users[1]->id,
            'process_status_id' => $processStatus,
            'remark' => '',
        ];
        Recruitment::create($data);
    }
}
