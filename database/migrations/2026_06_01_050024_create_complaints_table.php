<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {

            $table->id();

            $table->string('tracking_code', 30)->unique();

            $table->foreignId('complaint_category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('is_anonymous')->default(false);

            $table->string('name')->nullable();

            $table->string('phone', 15)->nullable();

            $table->string('nik', 16)->nullable();

            $table->longText('description');

            $table->enum('status', [
                'waiting',
                'under_review',
                'on_process',
                'solved',
                'rejected'
            ])->default('waiting');

            $table->timestamp('submitted_at')->nullable();

            $table->timestamp('solved_at')->nullable();

            $table->timestamps();

            $table->index('tracking_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};