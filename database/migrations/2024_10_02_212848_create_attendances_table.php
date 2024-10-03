<?php

use App\Consts\AttendanceType;
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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Employee::class)->constrained()->cascadeOnDelete();
            $table->text('note')->nullable();
            $table->enum('type', [AttendanceType::IN->value, AttendanceType::OUT->value]);
            $table->boolean('is_ontime')->default(false);
            $table->boolean('is_in_office')->default(false);
            $table->double('lat');
            $table->double('lng');
            $table->timestamp('present_at');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
