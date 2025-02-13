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
        Schema::create('actual_recive', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('in_date');
            $table->foreignUuid('supplier_id')->constrained('supplier')->onDelete('cascade');
            $table->foreignUuid('material_id')->constrained('material')->onDelete('cascade');
            $table->foreignUuid('material_detail_id')->constrained('material_details')->onDelete('cascade');
            $table->foreignUuid('no_po_id')->constrained('po_no')->onDelete('cascade');
            $table->string('delivery_number');
            $table->string('weight');
            $table->string('total_coil');
            $table->foreignUuid('control_po_id')->constrained('control_po')->onDelete('cascade');
            $table->string('charge_number');
            $table->string('coil_no');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actual_recive');
    }
};
