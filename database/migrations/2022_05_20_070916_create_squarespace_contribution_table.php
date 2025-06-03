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
        Schema::create('squarespace_contribution', function (Blueprint $table) {
            $table->id();
            $table->integer('message_id')->index(); // Auto-generate index name
            $table->integer('contact_id')->nullable()->index(); // Auto-generate index name
            $table->integer('event_id')->nullable()->index(); // Auto-generate index name
            $table->integer('donation_id')->nullable()->index(); // Auto-generate index name
            $table->integer('touchpoint_id')->nullable()->index(); // Auto-generate index name
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_supplemental')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_zip')->nullable();
            $table->string('address_country')->nullable();
            $table->string('phone')->nullable();
            $table->string('retreat_description')->nullable();
            $table->string('offering_type')->nullable();
            $table->decimal('amount', 13, 2)->default('0.00');
            $table->string('fund')->nullable();
            $table->string('idnumber')->nullable();
            $table->text('comments', 65535)->nullable();
            $table->boolean('is_processed')->nullable()->default(0);
            $table->string('stripe_charge_id')->index(); // Auto-generate index name
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('squarespace_contribution');
    }
};
