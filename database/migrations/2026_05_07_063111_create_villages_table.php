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
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->integer('number');           // หมู่ที่ 1-6
            $table->string('name');              // ชื่อหมู่บ้าน เช่น บ้านท่าสาป
            $table->string('slug')->unique();
            $table->text('description')->nullable();  // ประวัติหมู่บ้าน
            $table->string('highlight')->nullable();  // จุดเด่น
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
