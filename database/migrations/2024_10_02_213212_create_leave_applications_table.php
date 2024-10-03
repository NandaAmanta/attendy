<?php

use App\Consts\LeaveType;
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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Employee::class)->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->enum('type', [LeaveType::SICK->value, LeaveType::GENERAL->value]);
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->string('image_path')->nullable();
            $table->enum('status', [
                \App\Consts\LeaveStatus::DRAFT->value,
                \App\Consts\LeaveStatus::APPROVED->value,
                \App\Consts\LeaveStatus::REJECTED->value,
                \App\Consts\LeaveStatus::WAITING_FOR_APPROVAL->value,

            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
