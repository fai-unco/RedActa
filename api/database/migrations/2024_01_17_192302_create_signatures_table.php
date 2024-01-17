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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('document_id')->unsigned();
            $table->foreign('document_id')->references('id')->on('documents');
            $table->bigInteger('redacta_user_id')->unsigned();
            $table->foreign('redacta_user_id')->references('id')->on('redacta_users');
            $table->bigInteger('stamp_id')->unsigned();
            $table->foreign('stamp_id')->references('id')->on('stamps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signatures');
    }
};
