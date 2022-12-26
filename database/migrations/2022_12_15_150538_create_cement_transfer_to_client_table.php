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
        Schema::create('cement_transfer_to_client', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->date('date');
            $table->integer('bags');
            $table->text('remark')->default('');
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
        Schema::dropIfExists('cement_transfer_to_client');
    }
};
