<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presenters', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('presenter_id')->unsigned()->index();
            $table->string('presenter_type');
            $table->bigInteger('our_object_id')->unsigned()->index();
            $table->foreign('our_object_id')->references('id')->on('our_objects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presenters');
    }
}
