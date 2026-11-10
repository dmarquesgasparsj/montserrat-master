<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('pilot_totp_secret')->nullable();
            $table->unsignedBigInteger('pilot_totp_step')->nullable();
            $table->unsignedInteger('pilot_access_version')->default(0);
        });

        Schema::create('pilot_security_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('actor_user_id')->nullable();
            $table->unsignedInteger('target_user_id')->nullable();
            $table->string('action', 40);
            $table->string('role', 20)->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pilot_security_events');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pilot_totp_secret', 'pilot_totp_step', 'pilot_access_version']);
        });
    }
};
