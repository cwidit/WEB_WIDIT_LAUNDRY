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
    Schema::create('trans_laundry_pickup', function (Blueprint $table) {
        $table->id();

        $table->foreignId('id_order')
              ->constrained('trans_order')
              ->onUpdate('cascade')
              ->onDelete('restrict');

        $table->foreignId('id_customer')
              ->constrained('customer')
              ->onUpdate('cascade')
              ->onDelete('restrict');

        $table->dateTime('pickup_date');

        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('trans_laundry_pickup');
}
};
