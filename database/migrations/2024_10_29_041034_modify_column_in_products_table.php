<?php

use App\Models\Service;

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
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropColumn('promo_price');
            $table->dropColumn('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->decimal('promo_price', 10, 2)->nullable();
            $table->foreignIdFor(Service::class, 'service_id')->nullable();
        });
    }
};
