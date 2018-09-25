<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('number', function (Blueprint $table) {
            $table->unsignedInteger('tariff_id');
            $table->foreign('tariff_id')->references('id')->on('tariffs');
        });

        Schema::table('tariffs', function (Blueprint $table) {
            $table->string('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('number', function (Blueprint $table) {
            $table->dropForeign('tariff_id');
            $table->dropColumn('tariff_id');
        });

        Schema::table('tariffs', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
