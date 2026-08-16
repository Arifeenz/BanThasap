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
        Schema::create('village_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->boolean('is_cover')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ย้ายรูปเดิม (คอลัมน์ image ของ villages) มาเป็นรูปปกในตารางใหม่ ไม่ให้ข้อมูลหาย
        DB::table('villages')->whereNotNull('image')->get(['id', 'image'])->each(function ($village) {
            DB::table('village_images')->insert([
                'village_id' => $village->id,
                'image' => $village->image,
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
        Schema::dropIfExists('village_images');
    }
};
