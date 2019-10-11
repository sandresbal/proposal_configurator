<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('functionalities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('device', 200);
            $table->string('atom');
            $table->string('name', 200);
            $table->longtext('description_short')->nullable();
            $table->longtext('description_long')->nullable();
            $table->float('price_monthly',8,2)->nullable();
            $table->float('price_setup',8,2)->nullable();
            //$table->string('payment')->nullable();
            $table->string('units')->nullable();
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
        Schema::dropIfExists('functionalities');
    }
}
