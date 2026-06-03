<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaint_responses', function (Blueprint $table) {

            $table->id();

            $table->foreignId('complaint_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->constrained('users');

            $table->longText('solution');
            $table->boolean('is_current')
                ->default(true);

            $table->enum('approval_status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users');

            $table->text('review_note')->nullable();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index('approval_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_responses');
    }
};