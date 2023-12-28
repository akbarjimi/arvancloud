<?php

use App\Models\Transaction;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->unsignedBigInteger('amount');
            $table->unsignedTinyInteger('type');
            $table->unsignedBigInteger('balance')->default(0);
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();
            $table->innoDb();
            $table->collation('utf8mb4_persian_ci');
        });

        Schema::create('transaction_voucher', function (Blueprint $table) {
            $table->foreignIdFor(Voucher::class);
            $table->foreignIdFor(Transaction::class);
            $table->innoDb();
            $table->collation('utf8mb4_persian_ci');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_voucher');
        Schema::dropIfExists('transactions');
    }
};
