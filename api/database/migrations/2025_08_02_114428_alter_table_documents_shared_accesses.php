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
            $table->bigInteger('shared_by')->unsigned()->nullable();
            $table->foreign('shared_by')->references('id')->on('redacta_users');
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
            $table->dropForeign(['shared_by']);
            $table->dropColumn('shared_by');
        });
    }
};
