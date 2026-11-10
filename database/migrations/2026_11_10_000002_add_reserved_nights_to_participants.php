<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participant', function (Blueprint $table) {
            $table->date('room_starts_on')->nullable();
            $table->date('room_ends_on')->nullable();
            $table->index('room_id');
        });
    }

    public function down(): void
    {
        Schema::table('participant', function (Blueprint $table) {
            $table->dropIndex(['room_id']);
            $table->dropColumn(['room_starts_on', 'room_ends_on']);
        });
    }
};
