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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->decimal('amount', 10, 2);

            $table->char('currency', 3)
                ->default('BRL');

            $table->string('description')
                ->nullable();

            $table->enum('status', [
                'pending',
                'processing',
                'approved',
                'denied',
                'failed',
                'cancelled',
            ])->default('pending');

            $table->string('processor_reference')
                ->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
