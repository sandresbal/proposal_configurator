<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('receiver')->nullable();
            $table->string('creator')->nullable();
            $table->integer('commitment')->nullable();
            $table->unsignedBigInteger('cdn')->nullable();
            $table->unsignedBigInteger('liveplan')->nullable();
            $table->string('cms')->nullable();
            $table->unsignedBigInteger('bizmodel')->nullable();
            $table->string('package')->nullable();
            $table->string('soporte24')->nullable();
            $table->float('cdn_cost_monthly',8,2)->nullable();
            $table->float('cdn_cost_setup',8,2)->nullable(); 
            $table->float('CMS_cost_monthly',8,2)->nullable();
            $table->float('CMS_cost_extras_monthly',8,2)->nullable();
            $table->float('CMS_cost_extras_setup',8,2)->nullable();     
            $table->float('web_cost_monthly',8,2)->nullable();
            $table->float('web_extras_cost_monthly',8,2)->nullable();
            $table->float('web_extras_cost_setup',8,2)->nullable();
            $table->float('mobile_cost_monthly',8,2)->nullable();
            $table->float('mobile_extras_cost_monthly',8,2)->nullable();
            $table->float('mobile_extras_cost_setup',8,2)->nullable();
            $table->integer('num_mov_dev')->nullable();
            $table->float('tv_cost_monthly',8,2)->nullable();
            $table->float('tv_extras_cost_monthly',8,2)->nullable();
            $table->float('tv_extras_cost_setup',8,2)->nullable();
            $table->integer('num_tv_dev')->nullable();
            $table->float('price_live_month',8,2)->nullable();
            $table->float('soporte24_cost_monthly',8,2)->nullable();
            $table->float('monthly_cost_total',8,2)->nullable();
            $table->float('set_up_cost_total',8,2)->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('proposals');
    }
}
