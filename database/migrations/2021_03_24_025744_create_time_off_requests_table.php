<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeOffRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_off_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id')->nullable()->default(null);
            $table->uuid('time_off_type_id')->nullable()->default(null);
            $table->text('remark')->nullable()->default('text');
            $table->dateTime('from_date')->nullable()->default(DB::raw('NOW()'));
            $table->dateTime('to_date')->nullable()->default(DB::raw('NOW()'));
            $table->uuid('time_off_status_id')->nullable()->default(null);
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
        Schema::dropIfExists('time_off_requests');
    }
}
