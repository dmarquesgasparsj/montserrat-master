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
        Schema::create('stripe_balance_transaction', function (Blueprint $table) {
            $table->id();
            $table->string('payout_id')->index(); // Auto-generate index name
            $table->string('balance_transaction_id')->index(); // Auto-generate index name
            $table->string('customer_id')->nullable()->index(); // Auto-generate index name
            $table->string('charge_id')->index(); // Auto-generate index name
            $table->dateTime('payout_date')->nullable()->index(); // Auto-generate index name
            $table->string('description')->nullable();
            $table->string('note')->nullable();
            $table->string('type')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('zip')->nullable();
            $table->string('cc_last_4')->nullable();
            $table->decimal('total_amount', 13, 2)->nullable()->default('0.00');
            $table->decimal('fee_amount', 13, 2)->nullable()->default('0.00');
            $table->decimal('net_amount', 13, 2)->nullable()->default('0.00');
            $table->integer('contact_id')->nullable()->index(); // Auto-generate index name
            $table->integer('payment_id')->nullable()->index(); // Auto-generate index name
            $table->dateTime('reconcile_date')->nullable();
            $table->dateTime('available_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_balance_transaction'); // Corrected table name
    }
};
