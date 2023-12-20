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
        Schema::create('stamps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('full_name');
            $table->string('position')->nullable();
            $table->bigInteger('redacta_user_id')->unsigned();
            $table->foreign('redacta_user_id')->references('id')->on('redacta_users');
            $table->string('description');
        });

        Schema::table('stamps', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('stamps');
        Schema::table('stamps', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
