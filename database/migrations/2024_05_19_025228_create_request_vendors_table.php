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
        Schema::create('request_vendors', function (Blueprint $table) {
            $table->id();
            $table->json('service_id');
            $table->string('work_contents')->nullable();
            $table->date('desired_start')->nullable();
            $table->date('desired_end')->nullable();
            $table->string('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_vendors');
    }
};