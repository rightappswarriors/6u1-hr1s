<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRssysM08AddIsOffice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rssys.m08', function(Blueprint $table) {
            $table->boolean('is_office')->default(false);
        });

        $isOfficeArr = [
            '7611'      => true,
            '1061'      => true,
            'CENRO'     => true,
            '1081'      => true,
            '1031'      => true,
            '8711'      => true,
            '1101'      => true,
            '1071'      => true,
            '1051'      => true,
            '8751'      => true,
            '8731'      => true,
            '1032'      => true,
            '1131'      => true,
            '1011'      => true,
            '1041'      => true,
            '1091'      => true,
            '8721'      => true,
            '1016'      => true,
            '1013'      => true,
            '1021'      => true,
            '1022'      => true,
            '1131-1'    => true,
            '1141'      => true,
            '1158-1'    => true,
        ];


        DB::beginTransaction();
            foreach ($isOfficeArr as $ccCode => $value) {
                DB::table('rssys.m08')
                    ->where('cc_code', '=', $ccCode)
                    ->update(['is_office' => $value]);

            }
        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rssys.m08', function(Blueprint $table) {
            $table->dropColumn(['is_office']);
        });
    }
}
