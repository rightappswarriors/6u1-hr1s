<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrEmployeeHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hris.hr_employee_history', function(Blueprint $table) {
            $table->string('empid', 8);
            $table->string('lastname', 20)->nullable();
            $table->string('firstname', 20)->nullable();
            $table->string('mi', 2)->nullable();
            $table->string('positions', 8)->nullable();
            $table->string('department', 8)->nullable();
            $table->date('date_hired')->nullable();
            $table->date('contractual_date')->nullable();
            $table->date('prohibition_date')->nullable();
            $table->date('date_regular')->nullable();
            $table->date('date_resigned')->nullable();
            $table->date('date_terminated')->nullable();
            $table->string('empstatus', 3)->nullable();
            $table->integer('contract_days')->nullable();
            $table->string('prc', 100)->nullable();
            $table->string('ctc', 100)->nullable();
            $table->string('rate_type', 8)->nullable();
            $table->double('pay_rate')->nullable();
            $table->string('biometric', 100)->nullable();
            $table->string('sss', 100)->nullable();
            $table->string('pagibig', 100)->nullable();
            $table->string('philhealth', 100)->nullable();
            $table->string('payroll_account', 100)->nullable();
            $table->string('tin', 100)->nullable();
            $table->string('tax_bracket', 20)->nullable();
            $table->string('sex', 6)->nullable();
            $table->date('birth')->nullable();
            $table->string('civil_status', 10)->nullable();
            $table->string('religion', 50)->nullable();
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->string('father', 30)->nullable();
            $table->string('father_address', 50)->nullable();
            $table->string('father_contact', 30)->nullable();
            $table->string('father_job', 20)->nullable();
            $table->string('mother', 30)->nullable();
            $table->string('mother_address', 30)->nullable();
            $table->string('mother_contact', 30)->nullable();
            $table->string('mother_job', 30)->nullable();
            $table->string('emp_contact', 30)->nullable();
            $table->string('home_tel', 30)->nullable();
            $table->string('email', 30)->nullable();
            $table->string('home_address', 50)->nullable();
            $table->string('emergency_name', 30)->nullable();
            $table->string('emergency_contact', 30)->nullable();
            $table->string('em_home_address', 50)->nullable();
            $table->string('relationship', 20)->nullable();
            $table->string('fixed_rate', 2)->nullable();
            $table->string('fixed_sched', 2)->nullable();
            $table->string('graduate', 100)->nullable();
            $table->string('primary_ed', 100)->nullable();
            $table->string('tertiary_ed', 100)->nullable();
            $table->string('secondary_ed', 100)->nullable();
            $table->string('post_graduate', 150)->nullable();
            $table->text('emptype')->nullable();
            $table->text('accountnumber')->nullable();
            $table->boolean('isheadoffacility')->default(false);
            $table->integer('increment')->nullable();
            $table->string('deletedby', 10);
            $table->date('t_date');
            $table->time('t_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hris.hr_employee_history');
    }
}
