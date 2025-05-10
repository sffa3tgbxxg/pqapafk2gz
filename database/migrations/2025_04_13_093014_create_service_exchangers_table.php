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
        Schema::create('service_exchangers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('exchanger_id');
            $table->text('api_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->boolean('active')->default(false);
            $table->float('fee')->default(0);
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('exchanger_id')->references('id')->on('exchangers')->onDelete('cascade');

            $table->unique(['service_id', 'exchanger_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_exchangers');
    }
};
