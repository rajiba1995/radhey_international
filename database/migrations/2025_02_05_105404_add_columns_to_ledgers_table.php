<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ledgers', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id')->nullable()->after('transaction_date');
            $table->string('purpose')->nullable()->after('paid_amount');
            $table->text('purpose_description')->nullable()->after('purpose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ledgers', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'purpose', 'purpose_description']);
        });
    }
};
