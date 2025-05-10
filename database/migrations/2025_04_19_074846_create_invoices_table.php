<?php

use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('exchanger_id')->nullable()->default(null);
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->text('external_id')->nullable()->default(null);
            $table->unsignedBigInteger('currency_id')->nullable()->default(null);
            $table->decimal('amount_in', 19, 2)->default(0);
            $table->decimal('amount_out', 19, 2)->default(0);
            $table->string('status')->default(Invoice::SEARCH);
            $table->text('comment')->nullable()->default(null);
            $table->text("requisites")->nullable()->default(null);
            $table->json('details')->nullable()->default(null);
            $table->timestamp('expiry_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('exchanger_id')->references('id')->on('exchangers');
            $table->foreign('user_id')->references('id')->on('users_services');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
