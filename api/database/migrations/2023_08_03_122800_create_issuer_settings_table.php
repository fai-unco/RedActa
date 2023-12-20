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
        Schema::create('issuer_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('suggested_operative_section_beginning_id')->unsigned();
            $table->foreign('suggested_operative_section_beginning_id')->references('id')->on('operative_section_beginnings');
            $table->bigInteger('suggested_true_copy_stamp_id')->nullable()->unsigned();
            $table->foreign('suggested_true_copy_stamp_id')->references('id')->on('stamps');
            $table->bigInteger('issuer_id')->unsigned();
            $table->foreign('issuer_id')->references('id')->on('issuers');
            $table->bigInteger('suggested_heading_id')->nullable()->unsigned();
            $table->foreign('suggested_heading_id')->references('id')->on('headings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issuer_settings');
    }
};