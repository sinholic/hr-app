<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetNullableColumnsToRecruitmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruitments', function (Blueprint $table) {
            $table->uuid('department_id')->nullable()->change();
            $table->string('job_position')->nullable()->change();
            $table->integer('number_of_people_requested')->nullable()->change();
            $table->text('requirements')->nullable()->change();
            $table->date('deadline')->nullable()->change();
            $table->string('sallary_proposed')->nullable()->change();
            $table->uuid('priority_id')->nullable()->change();
            $table->uuid('request_status_id')->nullable()->change();
            $table->uuid('requested_by_user')->nullable()->change();
            $table->uuid('process_status_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruitments', function (Blueprint $table) {
            $table->uuid('department_id')->change();
            $table->string('job_position')->change();
            $table->integer('number_of_people_requested')->change();
            $table->text('requirements')->change();
            $table->date('deadline')->change();
            $table->string('sallary_proposed')->change();
            $table->uuid('priority_id')->change();
            $table->uuid('request_status_id')->change();
            $table->uuid('requested_by_user')->change();
            $table->uuid('process_status_id')->change();
        });
    }
}
