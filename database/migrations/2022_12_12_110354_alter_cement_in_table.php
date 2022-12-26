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
        Schema::rename('cement_in', 'cement_purchase',  function (Blueprint $table) {
            $table->text('remark')->after('site_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('cement_purchase', 'cement_in', function (Blueprint $table) {
            $table->dropColumn('remark');
        });
    }
};
