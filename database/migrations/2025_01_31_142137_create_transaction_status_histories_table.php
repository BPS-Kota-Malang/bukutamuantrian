<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->string('old_status')->nullable(); // Previous status
            $table->string('new_status'); // New status
            $table->timestamp('changed_at')->useCurrent(); // When the change happened
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_status_histories');
    }
};
