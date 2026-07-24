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
        Schema::table('villages', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('highlight');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('village_id')->nullable()->after('contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('village_id');
        });
    }
};
