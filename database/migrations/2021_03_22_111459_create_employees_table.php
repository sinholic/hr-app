<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100)->nullable()->default('text');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->uuid('department_id')->nullable()->default(null);
            $table->string('current_position', 100)->nullable()->default('text');
            $table->dateTime('promotion_date')->nullable()->default(DB::raw('NOW()'));
            $table->text('internal_memo')->nullable()->default('text');
            $table->string('salary', 100)->nullable()->default('text');
            $table->dateTime('payroll_date')->nullable()->default(DB::raw('NOW()'));
            $table->text('benefits')->nullable()->default('text');
            $table->text('remark')->nullable()->default('text');
            $table->string('file_cv', 100)->nullable()->default('text');
            $table->boolean('is_employee_can_login')->nullable()->default(false);
            $table->uuid('user_id')->nullable()->default(null);
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
        Schema::dropIfExists('employees');
    }
}
