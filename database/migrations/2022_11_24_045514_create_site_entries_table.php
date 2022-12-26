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
        Schema::create('site_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('activity_id');
            $table->integer('qty');
            $table->integer('skilled_workers');
            $table->integer('skilled_workers_overtime');
            $table->integer('unskilled_workers');
            $table->integer('unskilled_workers_overtime');
            $table->text('images')->nullable();
            $table->longText('remarks')->nullable();
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
        Schema::dropIfExists('site_entries');
    }
};
