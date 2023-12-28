<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('used')->default(0);
            $table->unsignedBigInteger('use')->default(0);
            $table->unsignedBigInteger('value')->default(0);
            $table->timestamp('expire_at');
            $table->timestamps();
            $table->softDeletes();
            $table->innoDb();
            $table->collation('utf8mb4_persian_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
