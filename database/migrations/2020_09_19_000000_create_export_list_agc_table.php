<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('export_list_agc', function (Blueprint $table) {
            $table->integer('contact_id');
            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('household_name')->nullable();
            $table->string('sort_name')->nullable();
            $table->string('street_address')->nullable();
            $table->string('supplemental_address_1')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('source')->nullable();
            $table->boolean('is_household')->default(0);
            $table->integer('event_count')->default(0);
            $table->dateTime('last_event_date')->nullable();
            $table->dateTime('last_donation_date')->nullable();
            $table->decimal('total_given', 13, 2)->default('0.00');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('contact_id'); // Let system auto-generate index name for uniqueness
            $table->unique('sort_name');  // Let system auto-generate index name for uniqueness
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_list_agc'); // Corrected table name for drop
    }
};
