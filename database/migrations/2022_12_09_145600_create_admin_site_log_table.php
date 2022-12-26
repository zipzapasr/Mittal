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
        Schema::create('admin_site_log', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->integer('updated_by_id');
            $table->integer('activity_id')->default(0);
            $table->integer('from');
            $table->integer('to');
            $table->text('remarks');
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
        Schema::dropIfExists('admin_site_log');
    }
};
