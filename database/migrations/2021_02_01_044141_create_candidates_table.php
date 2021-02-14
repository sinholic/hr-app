<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('recruitment_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('expected_salary');
            $table->string('proposed_salary')->nullable();
            $table->text('address')->nullable();
            $table->dateTime('interview_date')->nullable();
            $table->string('test_result')->nullable();
            $table->string('interview_result')->nullable();
            $table->string('curriculum_vitae');
            $table->text('remark')->nullable();
            $table->uuid('candidate_status_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
