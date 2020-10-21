<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('meta_description')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('articables', function (Blueprint $table) {
            $table->id();
            $table->integer('articable_id')->nullable();
            $table->string('articable_type')->nullable();
            $table->integer('article_id')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('articables');
    }
}
