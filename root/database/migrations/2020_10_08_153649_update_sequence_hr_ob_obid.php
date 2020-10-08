<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSequenceHrObObid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seqStart = $this->getLastObid() + 1;

        /**
        * auto increments obid and avoid possilbe duplicate value using $seqStart
        * 
        * note: we can't drop the sequence hris.hr_hr_other_deductions_od_id_seq cause id might be dependent on it 
        * and we can't do a drop cascade cause we will lose data, that's why we do a create if not exists and alter sequnce as a work around
        */
        DB::statement("create sequence if not exists hris.hr_ob_obid_seq increment 1 minvalue 1 maxvalue 2147483647 start " . $seqStart);
        DB::statement("alter sequence hris.hr_ob_obid_seq restart with " .$seqStart);
        DB::statement("alter table hris.hr_ob alter obid set default nextval('hris.hr_ob_obid_seq')");

        // drop unused sequence
        DB::statement("drop sequence if exists public.hr_ob_obid_seq");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // do nothing
    }

    // returns maximum value of id
    public function getLastObid() {
        return DB::table('hris.hr_ob')
            ->select('obid')
            ->orderBy('obid', 'desc')
            ->first()
            ->obid;
    }
}
