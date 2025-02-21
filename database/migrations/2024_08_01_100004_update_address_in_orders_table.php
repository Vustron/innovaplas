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
            $table->dropColumn('address');

            $table->string('region')->nullable()->after('order_status_id');
            $table->string('city')->nullable()->after('region');
            $table->string('province')->nullable()->after('city');
            $table->string('barangay')->nullable()->afterrR('province');
            $table->string('street')->nullable()->after('barangay');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
