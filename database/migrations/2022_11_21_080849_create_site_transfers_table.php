<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_transfers', function (Blueprint $table) {
            // site_from and site_to are ids in Site Table
            $table->id();
            $table->date('date');
            $table->integer('site_from');
            $table->integer('site_to');
            $table->integer('num_bags');
            $table->tinyInteger('status')->default('1');
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
        Schema::dropIfExists('site_transfers');
    }
};
