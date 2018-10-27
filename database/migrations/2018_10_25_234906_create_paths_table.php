<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paths', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('point_a_id')->unsigned();
            $table->integer('point_b_id')->unsigned();
            $table->unique(['point_a_id', 'point_b_id']);

            $table->foreign('point_a_id')->references('id')->on('points');
            $table->foreign('point_b_id')->references('id')->on('points');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paths');
    }
}
