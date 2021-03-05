<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_status_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('candidate_id')->nullable()->default(null);
            $table->uuid('candidate_status_id')->nullable()->default(null);
            $table->dateTime('action_datetime')->nullable()->default(DB::raw('NOW()'));
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
        Schema::dropIfExists('candidate_status_logs');
    }
}
