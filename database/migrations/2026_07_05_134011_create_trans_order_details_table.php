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
        Schema::create('trans_order_detail', function (Blueprint $table) {
        $table->id();

        $table->foreignId('id_order')
              ->constrained('trans_order')
              ->onUpdate('cascade')
              ->onDelete('restrict');

        $table->foreignId('id_service')
              ->constrained('type_of_service')
              ->onUpdate('cascade')
              ->onDelete('restrict');

        $table->integer('qty');
        $table->double('subtotal', 10, 2);
        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('trans_order_detail');
}
};
