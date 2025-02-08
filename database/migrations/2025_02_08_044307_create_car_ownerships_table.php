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
        Schema::create('car_ownerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('owners')->onDelete('NO ACTION');
            $table->foreignId('car_model_id')->constrained('car_models')->onDelete('NO ACTION');
            $table->foreignId('car_plate_id')->constrained('car_plates')->onDelete('NO ACTION');
            $table->enum('registration_type', ['primary', 'reregistration']);
            $table->dateTime('registered_at')->nullable();
            $table->dateTime('re_registered_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_ownerships');
    }
};
