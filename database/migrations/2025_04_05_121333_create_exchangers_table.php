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
        Schema::create('exchangers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->float("fee");
            $table->float('min_amount')->default(0);
            $table->float('max_amount')->default(9999999);
            $table->boolean('auto_withdraw')->default(false);
            $table->float('min_withdraw');
            $table->text('endpoint');
            $table->text('image')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchangers');
    }
};
