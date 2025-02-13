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
        Schema::create('material', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('supplier_id')->constrained('supplier')->onDelete('cascade');
            $table->string('material_name');
            $table->timestamps();
        });

        // Tabel untuk menyimpan detail diameter dan kg_coil
        Schema::create('material_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('material_id')->constrained('material')->onDelete('cascade');
            $table->string('diameter');
            $table->string('kg_coil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_details');
        Schema::dropIfExists('material');
    }
};
