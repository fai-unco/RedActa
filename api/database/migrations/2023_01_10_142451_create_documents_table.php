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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('body');
            $table->timestamps();
            $table->string('issue_place');
            $table->date('issue_date');
            $table->string('destinatary')->nullable();
            $table->string('subject')->nullable();
            $table->boolean('ad_referendum')->default(false);

            $table->bigInteger('redacta_user_id')->unsigned();
            $table->foreign('redacta_user_id')->references('id')->on('redacta_users');
            $table->bigInteger('document_type_id')->unsigned();
            $table->foreign('document_type_id')->references('id')->on('document_types');
            $table->bigInteger('document_copy_id')->unsigned();
            $table->foreign('document_copy_id')->references('id')->on('document_copies');
            $table->bigInteger('issuer_id')->unsigned();
            $table->foreign('issuer_id')->references('id')->on('issuers');
            $table->string('issue_place');
            $table->date('issue_date');
            $table->string('destinatary')->nullable();
            $table->string('subject')->nullable();
            $table->boolean('ad_referendum')->default(false);

        });
        Schema::table('documents', function (Blueprint $table) {
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
        Schema::dropIfExists('documents');
    }
};
