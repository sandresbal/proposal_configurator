<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstraintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('functionality_bizmodel', function($table) {
            $table->foreign('functionality_id')->references('id')->on('functionalities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('bizmodel_id')->references('id')->on('bizmodels')->onUpdate('cascade')->onDelete('cascade');

        });
        Schema::table('proposal_devices', function($table) {
            $table->foreign('proposal_id')->references('id')->on('proposals')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('device_id')->references('id')->on('devices')->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('proposal_functionalities', function($table) {
            $table->foreign('proposal_id')->references('id')->on('proposals')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('functionality_id')->references('id')->on('functionalities')->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('proposals', function($table) {
            $table->foreign('cdn')->references('id')->on('cdns')->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('proposals', function($table) {
            $table->foreign('liveplan')->references('id')->on('liveplans')->onUpdate('cascade')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('constraints');
    }
}
