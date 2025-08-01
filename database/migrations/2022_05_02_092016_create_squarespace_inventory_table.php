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
        Schema::create('squarespace_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index(); // Let system auto-generate index name
            $table->integer('custom_form_id');
            $table->integer('variant_options')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('squarespace_inventory');
    }
};
