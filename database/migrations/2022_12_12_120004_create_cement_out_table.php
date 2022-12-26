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
        Schema::create('cement_out', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('bags');
            $table->integer('from_site_id');
            $table->integer('to_site_id');
            $table->text('remark');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('cement_out');
    }
};
