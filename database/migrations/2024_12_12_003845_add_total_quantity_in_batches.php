<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ProductBatch;
use App\Models\RawMaterialBatch;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_batches', function (Blueprint $table) {
            $table->integer('total_quantity')->nullable()->after('quantity');
        });

        $batches = ProductBatch::all();
        foreach ($batches as $batch) {
            $batch->total_quantity = $batch->quantity;
            $batch->save();
        }

        Schema::table('raw_material_batches', function (Blueprint $table) {
            $table->integer('total_quantity')->nullable()->after('quantity');
        });

        $batches = RawMaterialBatch::all();
        foreach ($batches as $batch) {
            $batch->total_quantity = $batch->quantity;
            $batch->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            //
        });
    }
};
