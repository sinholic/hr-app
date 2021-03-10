<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CandidateStatusLog;
use App\Models\Candidate;
use App\Models\Option;
use DB;

class BatchInsertLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csl:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Candidate status logs insert to latest status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sequences = [
            'WAITING FOR CONFIRMATION FROM USER',
            'CV SUITABLE',
            'FORM SCREENING SENT',
            'FORM SCREENING RECEIVED',
            'NOT SUITABLE TO INTERVIEW',
            'WAITING FOR INTERVIEW WITH USER',
            "WAITING FOR USER'S DECISION",
            'SUITABLE FOR OL',
            'OFFERING LETTER SENT',
            'ON BOARDING'
        ];
        $headers = ['ID' ,'Name', 'Email'];
        $candidates = Candidate::whereNotIn('id',array(
            "078d5849-9b0f-49a1-9f2b-64da089583e8",
            "2539d6ea-56ea-4d84-a53b-f29f2771bd0d",
            "2a3e04f7-dc36-4aa3-998f-a5e05be0396e",
            "2c8f72d2-99b2-4ef1-a023-8bc9d193ba4a",
            "3eefcd77-73b1-4946-87eb-e09dc2dde6ae",
            "4a15fa74-f216-4ecf-be32-828126501176",
            "6579cf72-e4cc-428f-b125-34daf1cc5abc",
            "66cd9218-4e66-4400-beb7-7948b2e3c97f",
            "67d54fd1-b00b-4fb3-a168-3f29fd6a28e4",
            "6cc3d899-abc9-4ac9-bc9e-9843ba41a7d6",
            "7235832c-7b1f-4a83-bc98-1d7476253c4c",
            "a01808dd-86e2-4d89-b2ca-f3a565546a69",
            "ac340f96-2cc3-47ff-9b56-7710550dcd64",
            "ae13491f-e3f3-4973-8193-cc1a831347e4",
            "cc8de442-de77-472c-8964-ddb36f9a1ede",
            "ce7e6ff7-80e0-42f8-a843-0bb66e078768",
            "eab62d74-75bf-41e4-bd55-d3cea29b9dc7",
            "f3b16cd0-5f8d-4b05-87cf-94a4cf49acae",
            "f807bd69-7b33-4336-b8c2-a8fa003eaa0e"
        ))
        ->orderBy('name')
        ->get();
        // dd($candidates->count());
        // $this->table($headers, $candidates);
        $candidate_statuses = Option::where('type', 'CANDIDATE_STATUS')
        ->whereIn('name', $sequences)
        ->get()
        ->toArray();

        foreach ($candidates as $candidate) {
            foreach ($sequences as $sequence) {
                $candidate_status = Option::where('type', 'CANDIDATE_STATUS')->where('name', $sequence)->first();
                CandidateStatusLog::updateOrCreate([
                    'candidate_id'              =>  $candidate->id,
                    'candidate_status_id'       =>  $candidate_status->id,
                ],[
                    'action_datetime'           =>  \Carbon\Carbon::now()
                ]);
                $this->info($candidate->name);
                $this->info($candidate->candidate_status_id);
                $this->info($candidate_status->id);
                if ($candidate->candidate_status_id == $candidate_status->id) {
                    // die();
                    break;
                }
            }   
        }
        // $this->table($headers, $candidate_statuses);
    }
}
