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
        Schema::table('documents', function (Blueprint $table) {
            $table->bigInteger('operative_section_beginning_id')->unsigned();
            $table->foreign('operative_section_beginning_id')->references('id')->on('operative_section_beginnings');
            $table->bigInteger('true_copy_stamp_id')->nullable()->unsigned();
            $table->foreign('true_copy_stamp_id')->references('id')->on('stamps');
            $table->bigInteger('heading_id')->nullable()->unsigned();
            $table->foreign('heading_id')->references('id')->on('headings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            //
        });
    }
};
