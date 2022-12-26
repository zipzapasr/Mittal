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
        Schema::table('cement_in', function (Blueprint $table) {
            $table->tinyInteger('status')->default('1');
            $table->integer('project_manager_id')->after('supplier_id');
            $table->integer('site_id')->after('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cement_in', function (Blueprint $table) {
            //
        });
    }
};
