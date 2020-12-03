<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLandingToOurObjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('our_objects', function (Blueprint $table) {
            $table->boolean('landing')->after('price')->default(false);
        });

        DB::table('our_objects')->update([
            'landing' => false,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('our_objects', function (Blueprint $table) {
            $table->dropColumn('landing');
        });
    }
}
