<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetNullableColumnsToCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('expected_salary')->nullable()->change();
            $table->string('curriculum_vitae')->nullable()->change();
            $table->uuid('candidate_status_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('expected_salary')->change();
            $table->string('curriculum_vitae')->change();
            $table->uuid('candidate_status_id')->change();
        });
    }
}
