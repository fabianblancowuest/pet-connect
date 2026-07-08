<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->index('status');
            $table->index('size');
            $table->index(['status', 'species_id']);
            $table->index(['status', 'size']);
        });

        Schema::table('pet_images', function (Blueprint $table) {
            $table->index(['pet_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['size']);
            $table->dropIndex(['status', 'species_id']);
            $table->dropIndex(['status', 'size']);
        });

        Schema::table('pet_images', function (Blueprint $table) {
            $table->dropIndex(['pet_id', 'is_primary']);
        });
    }
};
