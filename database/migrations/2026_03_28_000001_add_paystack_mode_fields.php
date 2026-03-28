<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("settings", function (Blueprint $table) {
            if (! Schema::hasColumn("settings", "use_live_payment")) {
                $table->boolean("use_live_payment")->default(false)->after("account_balance");
            }
        });

        Schema::table("transactions", function (Blueprint $table) {
            if (! Schema::hasColumn("transactions", "paystack_live_mode")) {
                $table->boolean("paystack_live_mode")->nullable()->after("status");
            }
        });
    }

    public function down(): void
    {
        Schema::table("settings", function (Blueprint $table) {
            if (Schema::hasColumn("settings", "use_live_payment")) {
                $table->dropColumn("use_live_payment");
            }
        });

        Schema::table("transactions", function (Blueprint $table) {
            if (Schema::hasColumn("transactions", "paystack_live_mode")) {
                $table->dropColumn("paystack_live_mode");
            }
        });
    }
};
