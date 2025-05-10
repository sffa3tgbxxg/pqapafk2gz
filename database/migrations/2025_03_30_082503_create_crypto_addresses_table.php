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
        Schema::create('crypto_addresses', function (Blueprint $table) {
            $table->id();
            $table->text("address");
            $table->text("mnemonic");
            $table->float("balance")->default(0);
            $table->unsignedBigInteger("currency_id");
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_addresses');
    }
};
