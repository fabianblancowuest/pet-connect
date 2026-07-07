<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('message');
            $table->string('housing_type')->nullable()->after('phone');
            $table->boolean('has_outdoor_space')->nullable()->after('housing_type');
            $table->boolean('has_other_pets')->nullable()->after('has_outdoor_space');
            $table->text('other_pets_details')->nullable()->after('has_other_pets');
            $table->boolean('has_children')->nullable()->after('other_pets_details');
            $table->string('children_ages')->nullable()->after('has_children');
            $table->boolean('previous_experience')->nullable()->after('children_ages');
        });
    }

    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'housing_type', 'has_outdoor_space',
                'has_other_pets', 'other_pets_details', 'has_children',
                'children_ages', 'previous_experience',
            ]);
        });
    }
};
