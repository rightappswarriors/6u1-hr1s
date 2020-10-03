<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSequenceForRssysM08CcId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seqStart = $this->getLastCcId() + 1;

        // drop primary key
        DB::statement("alter table rssys.m08 drop constraint if exists m08_pkey");
        DB::statement("alter table rssys.m08 drop constraint if exists cc_code");

        // add primary key
        DB::statement("alter table rssys.m08 add primary key (cc_id)");

        /**
        * auto increments cc_id and avoid possilbe duplicate value using $seqStart
        * 
        * note: we can't drop the sequence rssys.m08_cc_id_id_seq cause cc_id might be dependent on it 
        * and we can't do a drop cascade cause we will lose data, that's why we do a create if not exists and alter sequnce as a work around
        */
        DB::statement("create sequence if not exists rssys.m08_cc_id_seq increment 1 minvalue 1 maxvalue 2147483647 start " . $seqStart);
        DB::statement("alter sequence rssys.m08_cc_id_seq restart with " .$seqStart);
        DB::statement("alter table rssys.m08 alter cc_id set default nextval('rssys.m08_cc_id_seq')");

        // drop unused sequence
        DB::statement("drop sequence if exists m08_cc_id_seq");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // drop primary key
        DB::statement("alter table rssys.m08 drop constraint if exists m08_pkey");

        // add primary key
        DB::statement("alter table rssys.m08 add primary key (cc_code)");
    }

    // returns maximum value of cc_id
    public function getLastCcId() {
        return DB::table('rssys.m08')
            ->select('cc_id')
            ->orderBy('cc_id', 'desc')
            ->first()
            ->cc_id;
    }
}
