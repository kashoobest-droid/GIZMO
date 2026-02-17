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
        Schema::table('users', function (Blueprint $table) {
            // Drop old address column if it exists
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
            
            // Add new address fields
            $table->string('country')->nullable()->after('phone');
            $table->string('street_name')->nullable()->after('country');
            $table->string('building_name')->nullable()->after('street_name');
            $table->string('floor_apartment')->nullable()->after('building_name');
            $table->string('landmark')->nullable()->after('floor_apartment');
            $table->string('city_area')->nullable()->after('landmark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['country', 'street_name', 'building_name', 'floor_apartment', 'landmark', 'city_area']);
        });
    }
};
