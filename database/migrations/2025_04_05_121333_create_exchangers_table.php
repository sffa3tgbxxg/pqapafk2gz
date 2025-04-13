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
            $table->boolean('auto_withdraw')->default(false);
            $table->float('min_withdraw');
            $table->text('url');
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
