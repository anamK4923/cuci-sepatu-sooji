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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->enum('delivery_method', ['antar_jemput', 'drop_off']);
            $table->text('alamat_pickup')->nullable();
            $table->dateTime('pickup_schedule')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['waiting_pickup', 'picked_up', 'in_process', 'done', 'delivered'])->default('waiting_pickup');
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->decimal('total_price', 12, 2);
            $table->string('midtrans_order_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
