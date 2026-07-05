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
    Schema::create('trans_order', function (Blueprint $table) {
        $table->id();

        $table->foreignId('id_customer')
              ->constrained('customer')
              ->onUpdate('cascade')
              ->onDelete('restrict');

        $table->string('order_code', 50);
        $table->date('order_date');
        $table->date('order_end_date');
        $table->tinyInteger('order_status');

        $table->timestamps();

        $table->dateTime('deleted_at')->nullable();

        $table->integer('order_pay');
        $table->integer('order_change');
        $table->integer('total');
    });
}

public function down(): void
{
    Schema::dropIfExists('trans_order');
}
};
