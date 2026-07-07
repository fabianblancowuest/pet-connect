<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn('has_children');
        });
    }

    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->boolean('has_children')->nullable()->after('other_pets_details');
        });
    }
};
