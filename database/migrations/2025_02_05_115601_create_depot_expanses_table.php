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
        Schema::create('depot_expanses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('expense_id');
            $table->unsignedBigInteger('service_slip_id')->nullable();
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->string('payment_for');
            $table->string('payment_in');
            $table->enum('bank_cash', ['Bank', 'Cash']);
            $table->string('voucher_no')->unique();
            $table->date('payment_date');
            $table->enum('payment_mode', ['Cash', 'Cheque', 'UPI', 'Bank Transfer']);
            $table->decimal('amount', 10, 2);
            $table->string('chq_utr_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('narration')->nullable();
            $table->string('created_from')->nullable();
            $table->boolean('is_gst')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            // Foreign Keys (Assuming Relationships Exist)
            // $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depot_expanses');
    }
};
