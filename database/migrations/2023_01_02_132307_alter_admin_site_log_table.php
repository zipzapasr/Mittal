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
        Schema::table('admin_site_log', function (Blueprint $table) {
            $table->dropColumn('from');
            $table->dropColumn('to');
            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_site_log', function (Blueprint $table) {
            $table->integer('from');
            $table->integer('to');
            $table->dropColumn('value');
        });
    }
};
