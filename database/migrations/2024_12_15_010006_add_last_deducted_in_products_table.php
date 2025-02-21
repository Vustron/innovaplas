<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\RawMaterial;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->date('last_deducted')->nullable();
        });

        foreach (Product::withTrashed()->get() as $product) {
            $product->last_deducted = $product->updated_at;
            $product->save();
        }

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->date('last_deducted')->nullable();
        });

        foreach (RawMaterial::all() as $material) {
            $material->last_deducted = $material->updated_at;
            $material->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
