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
        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->nullable();  // ธรรมชาติ, ประวัติศาสตร์, เรียนรู้
            $table->text('description')->nullable();
            $table->text('how_to_get')->nullable();   // วิธีเดินทาง
            $table->string('open_hours')->nullable();  // เวลาเปิด-ปิด
            $table->string('contact')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attractions');
    }
};
