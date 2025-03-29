<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade'); // Foreign key
            $table->string('account_name')->unique();
            $table->bigInteger('account_number')->unique();
            $table->enum('account_type', ['Personal', 'Business']);
            $table->enum('currency', ['USD', 'EUR', 'GBP']);
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes(); // For deactivation instead of hard delete
        });
    }

    public function down(): void {
        Schema::dropIfExists('accounts');
    }
};
