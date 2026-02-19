<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'admin_scopes')) {
                $table->json('admin_scopes')->nullable()->after('is_admin');
            }
            if (!Schema::hasColumn('users', 'is_master_admin')) {
                $table->boolean('is_master_admin')->default(false)->after('admin_scopes');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'admin_scopes')) {
                $table->dropColumn('admin_scopes');
            }
            if (Schema::hasColumn('users', 'is_master_admin')) {
                $table->dropColumn('is_master_admin');
            }
        });
    }
};
