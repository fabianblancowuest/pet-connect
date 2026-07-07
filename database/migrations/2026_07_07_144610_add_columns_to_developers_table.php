<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('role')->nullable()->after('name');
            $table->text('description')->nullable()->after('role');
            $table->string('image')->nullable()->after('description');
            $table->integer('sort_order')->default(0)->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn(['name', 'role', 'description', 'image', 'sort_order']);
        });
    }
};
