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
        Schema::create('fortcast', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('material_id')->constrained('material')->onDelete('cascade');
            $table->foreignUuid('material_detail_id')->constrained('material_details')->onDelete('cascade');
            $table->string('po')->nullable();
            $table->foreignUuid('control_po_id')->constrained('control_po')->onDelete('cascade');
            $table->foreignUuid('actual_receive_id')->constrained('actual_recive')->onDelete('cascade');
            $table->string('balance');
            $table->string('persentase');
            $table->string('kanban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fortcast');
    }
};
