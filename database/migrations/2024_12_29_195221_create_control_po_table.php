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
        Schema::create('control_po', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('supplier_id')->constrained('supplier')->onDelete('cascade');
            $table->foreignUuid('material_id')->constrained('material')->onDelete('cascade');
            $table->foreignUuid('material_detail_id')->constrained('material_details')->onDelete('cascade');
            $table->foreignUuid('no_po_id')->constrained('po_no')->onDelete('cascade');
            $table->string('schedule_incoming');
            $table->string('qty_coil');
            $table->string('qty_kg');
            $table->string('month');
            $table->enum('material_receiving_status', ['waiting', 'received', 'rejected'])->default('waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_po');
    }
};
