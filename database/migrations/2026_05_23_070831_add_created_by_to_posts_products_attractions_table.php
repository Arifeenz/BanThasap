<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('published_at');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('is_active');
        });

        Schema::table('attractions', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('attractions', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
};