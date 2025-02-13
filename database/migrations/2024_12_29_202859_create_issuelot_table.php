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
        Schema::create('issuelot', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('supplier_id')->constrained('supplier')->onDelete('cascade');
            $table->foreignUuid('material_id')->constrained('material')->onDelete('cascade');
            $table->foreignUuid('material_detail_id')->constrained('material_details')->onDelete('cascade');
            $table->string('tag_lot');
            $table->string('qa_tag');
            $table->string('charge_no');
            $table->string('qty_kg');
            $table->string('coil_no');
            $table->string('qr_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuelot');
    }
};
