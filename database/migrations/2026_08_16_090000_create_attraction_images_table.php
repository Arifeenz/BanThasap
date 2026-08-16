<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attraction_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attraction_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->boolean('is_cover')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ย้ายรูปเดิม (คอลัมน์ image ของ attractions) มาเป็นรูปปกในตารางใหม่ ไม่ให้ข้อมูลหาย
        DB::table('attractions')->whereNotNull('image')->get(['id', 'image'])->each(function ($attraction) {
            DB::table('attraction_images')->insert([
                'attraction_id' => $attraction->id,
                'image' => $attraction->image,
                'is_cover' => true,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attraction_images');
    }
};
