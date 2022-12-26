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
        Schema::table('cement_purchase', function (Blueprint $table) {
            $table->dropColumn('project_manager_id');
            $table->integer('employee_id')->after('site_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cement_purchase', function (Blueprint $table) {
            //
        });
    }
};
