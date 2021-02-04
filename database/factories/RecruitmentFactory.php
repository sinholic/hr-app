<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Recruitment;
use Faker\Generator as Faker;

$factory->define(Recruitment::class, function (Faker $faker) {
    return [
        'department_id' => '62cf25c4-6442-4829-8166-ec8902a6b919',
        'jobposition_id' => 'f3771bf7-141d-46ab-ad97-05a76e4c65cc',
        'number_of_people_requested' => 10,
        'number_of_people_approved' => 9,
        'requirements' => 'DevOps engineers build, test and maintain the infrastructure and tools to allow for the speedy development and release of software. DevOps practices aim to simplify the development process of software.',
        'deadline' => '2021-05-01',
        'sallary_proposed' => '30000000',
        'sallary_adjusted' => '20000000',
        'priority_id' => 'c38ed79d-8a97-4b47-8631-ed3ef384c092',
        'request_status_id' => '6db3c3e1-7c92-46fa-b5e3-eef419bcbb7f',
        'requested_by_user' => '1f419dac-9e40-4a67-93cd-4a5846b22d40',
        'change_request_status_by_user' => '3d15134c-18d3-4b5f-ad0c-6c656820579f',
        'process_status_id' => '4f6801a5-a03f-4f54-b344-ab0a3d29076b',
        'processed_by_user' => '6d64f30d-39a5-4d99-97be-1ca34b3da41e',
        'remark' => '',
    ];
});
