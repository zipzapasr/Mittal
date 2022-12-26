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
        Schema::table('site_entries', function (Blueprint $table) {
            $table->integer('cement_bags')->default('0')->after('unskilled_workers_overtime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_entries', function (Blueprint $table) {
            $table->dropColumn('cement_bags');
        });
    }
};
