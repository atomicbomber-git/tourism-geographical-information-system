<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('point_id')->unsigned();
            $table->integer('visitor_count')->unsigned();
            $table->decimal('fee', 19, 4)->unsigned();
            $table->integer('facility_count')->unsigned();
            $table->text('description');

            $table->foreign('point_id')
                ->references('id')
                ->on('points');

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
        Schema::dropIfExists('sites');
    }
}
