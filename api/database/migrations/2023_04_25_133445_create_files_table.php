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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('filename');

            $table->bigInteger('anexo_id')->nullable()->unsigned();
            $table->foreign('anexo_id')->references('id')->on('anexos')->onDelete('cascade');
            $table->bigInteger('redacta_user_id')->unsigned();
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
        Schema::dropIfExists('files');
    }
};
