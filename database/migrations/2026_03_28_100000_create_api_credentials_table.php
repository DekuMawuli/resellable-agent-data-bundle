<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('key_name')->unique();   // e.g. 'paystack_live_secret'
            $table->string('key_label');             // e.g. 'Paystack Live Secret Key'
            $table->string('key_group');             // e.g. 'paystack', 'external'
            $table->boolean('is_secret')->default(true); // false = URL/non-masked value
            $table->text('value')->nullable();       // AES-256 encrypted via 'encrypted' cast
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_credentials');
    }
};
