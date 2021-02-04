<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Candidate;
use Faker\Generator as Faker;

$factory->define(Candidate::class, function (Faker $faker) {
    return [
        'name'                  => $faker->name,
        'email'                 => $faker->unique()->freeEmail,
        'phone'                 => $faker->e164PhoneNumber,
        'expected_sallary'      => $faker->unixTime,
        'test_result'           => $faker->word,
        'interview_result'      => $faker->word,
        'curriculum_vitae'      => 'file.txt',
        'remark'                => 'Good enaf',
        'candidate_status_id'   => '3302d4c5-2d6f-4ba9-9d0d-cc924882c47f'
    ];
});
