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
            $table->string('operative_section_beginning');
            $table->string('ad_referendum_operative_section_beginning')->nullable();
            $table->string('true_copy_signatory_full_name')->nullable();
            $table->string('true_copy_signatory_role')->nullable();

            $table->bigInteger('issuer_id')->unsigned();
            $table->foreign('issuer_id')->references('id')->on('issuers')->onDelete('cascade');
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
