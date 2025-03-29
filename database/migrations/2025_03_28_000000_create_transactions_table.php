<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->foreignUuid('account_id')->constrained('accounts')->onDelete('cascade'); // Foreign key
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['credit', 'debit']);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Allows transaction reversal instead of hard delete
        });
    }

    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};
