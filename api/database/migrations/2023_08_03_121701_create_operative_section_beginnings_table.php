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
        Schema::create('operative_section_beginnings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('content');
            $table->bigInteger('issuer_id')->unsigned();
            $table->foreign('issuer_id')->references('id')->on('issuers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operative_section_beginnings');
    }
};
