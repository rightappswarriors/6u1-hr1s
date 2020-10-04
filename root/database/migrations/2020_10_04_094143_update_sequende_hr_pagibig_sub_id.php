<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSequendeHrPagibigSubId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seqStart = $this->getLastId() + 1;
        
        /**
        * auto increments id and avoid possilbe duplicate value using $seqStart
        * 
        * note: we can't drop the sequence hris.hr_pagibig_sub_id_seq cause id might be dependent on it 
        * and we can't do a drop cascade cause we will lose data, that's why we do a create if not exists and alter sequnce as a work around
        */
        DB::statement("create sequence if not exists hris.hr_pagibig_sub_id_seq increment 1 minvalue 1 maxvalue 2147483647 start " . $seqStart);
        DB::statement("alter sequence hris.hr_pagibig_sub_id_seq restart with " .$seqStart);
        DB::statement("alter table hris.hr_pagibig_sub alter id set default nextval('hris.hr_pagibig_sub_id_seq')");

        // drop unused sequence
        DB::statement("drop sequence if exists public.hr_pagibig_sub_id_seq");
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
    public function getLastId() {
        return DB::table('hris.hr_pagibig_sub')
            ->select('id')
            ->orderBy('id', 'desc')
            ->first()
            ->id;
    }
}
