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
        Schema::table('orders', function (Blueprint $table) {
            $table->date('estimated_delivery_date')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('shipment_status')->nullable()->default('pending')->after('tracking_number'); // Adjust column position if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['estimated_delivery_date', 'tracking_number', 'shipment_status']);
        });
    }
};
