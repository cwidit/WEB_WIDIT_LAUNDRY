<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('trans_order', function (Blueprint $table) {
        $table->integer('payment_status')->default(0); // 0 = Lunas, 1 = Hutang
        $table->integer('paid_amount')->default(0);    // Nominal yang dibayar
    });
}

public function down()
{
    Schema::table('trans_order', function (Blueprint $table) {
        $table->dropColumn(['payment_status', 'paid_amount']);
    });
}
};
