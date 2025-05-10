<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount', 19, 8);
            $table->float('ex_rate');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger("address_id");
            $table->enum('status', ['pending', 'canceled', 'paid'])->default('pending');
            $table->timestamp('expiry_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('address_id')->references('id')->on('crypto_addresses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_invoices');
    }
};
