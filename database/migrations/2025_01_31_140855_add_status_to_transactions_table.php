<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('status', ['Queue', 'Completed'])->default('Queue')->after('updated_at');
            $table->timestamp('status_changed_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['status', 'status_changed_at']);
        });
    }
};
