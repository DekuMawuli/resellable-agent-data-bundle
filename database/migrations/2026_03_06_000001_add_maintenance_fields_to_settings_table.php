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
        Schema::table("settings", function (Blueprint $table) {
            if (!Schema::hasColumn("settings", "maintenance_mode")) {
                $table->boolean("maintenance_mode")->default(false)->after("account_balance");
            }

            if (!Schema::hasColumn("settings", "maintenance_message")) {
                $table->string("maintenance_message")
                    ->nullable()
                    ->after("maintenance_mode");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("settings", function (Blueprint $table) {
            if (Schema::hasColumn("settings", "maintenance_message")) {
                $table->dropColumn("maintenance_message");
            }

            if (Schema::hasColumn("settings", "maintenance_mode")) {
                $table->dropColumn("maintenance_mode");
            }
        });
    }
};
