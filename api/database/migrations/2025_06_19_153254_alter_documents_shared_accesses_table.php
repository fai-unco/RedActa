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
            $table->renameColumn('redacta_user_id', 'document_shared_accessable_id');
            $table->dropForeign(['redacta_user_id']);
            $table->string('document_shared_accessable_type')->default('App\\Models\\RedactaUser');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->renameColumn('document_shared_accessable_id', 'redacta_user_id');
        $table->foreign('redacta_user_id')->references('id')->on('redacta_users');
        $table->dropColumn('document_shared_accessable_type');
    }
};
