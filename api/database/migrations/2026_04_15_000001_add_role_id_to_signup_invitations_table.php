<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('signup_invitations', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('token')->constrained('roles')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('signup_invitations', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};