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
        Schema::table('documents_shared_accesses', function (Blueprint $table) {
            $table->bigInteger('redacta_user_id')->unsigned()->nullable();
            $table->foreign('redacta_user_id')->references('id')->on('redacta_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents_shared_accesses', function (Blueprint $table) {
            $table->dropForeign(['redacta_user_id']);
            $table->dropColumn('redacta_user_id');
        });
    }
};
