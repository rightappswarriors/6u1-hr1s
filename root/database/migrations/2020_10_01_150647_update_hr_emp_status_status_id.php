<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHrEmpStatusStatusId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seqStart = $this->getLastStatusId() + 1;

        // drop primary key
        DB::statement("alter table hris.hr_emp_status drop constraint if exists hr_emp_status_pkey");

        /**
        * auto increments status_id and avoid possilbe duplicate value using $seqStart
        * 
        * note: we can't drop the sequence hris.hr_emp_status_status_id_seq cause status_id might be dependent on it 
        * and we can't do a drop cascade cause we will lose data, that's why we do a create if not exists and alter sequnce as a work around
        */
        DB::statement("create sequence if not exists hris.hr_emp_status_status_id_seq increment 1 minvalue 1 maxvalue 2147483647 start " . $seqStart);
        DB::statement("alter sequence hris.hr_emp_status_status_id_seq restart with " .$seqStart);
        DB::statement("alter table hris.hr_emp_status alter status_id set default nextval('hris.hr_emp_status_status_id_seq')");

        // add primary key
        DB::statement("alter table hris.hr_emp_status add primary key (status_id)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // drop primary key
        DB::statement("alter table hris.hr_emp_status drop constraint if exists hr_emp_status_pkey");

        // add primary key
        DB::statement("alter table hris.hr_emp_status add primary key (statcode)");
    }

    // returns maximum value of status_id 
    public function getLastStatusId() {
        return DB::table('hris.hr_emp_status')
            ->select('status_id')
            ->orderBy('status_id', 'desc')
            ->first()
            ->status_id;
    }
}
