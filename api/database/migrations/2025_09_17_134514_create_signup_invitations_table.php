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
        Schema::create('signup_invitations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //Foreign key to redacta_users table
            $table->foreignId('redacta_user_id')->constrained('redacta_users')->onDelete('cascade');
            //Uuid as unique token for the invitation
            $table->string('token', 64)->unique();
            //Timestamp of when the invitation was used
            $table->timestamp('used_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signup_invitations');
    }
};
