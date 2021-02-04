<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('department_id');
            $table->uuid('jobposition_id');
            $table->integer('number_of_people_requested');
            $table->integer('number_of_people_approved')->nullable();
            $table->text('requirements');
            $table->date('deadline');
            $table->string('sallary_proposed');
            $table->string('sallary_adjusted')->nullable();
            $table->uuid('priority_id');
            $table->uuid('request_status_id');
            $table->uuid('requested_by_user');
            $table->uuid('change_request_status_by_user')->nullable();
            $table->uuid('process_status_id');
            $table->uuid('processed_by_user')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('recruitments');
    }
}
