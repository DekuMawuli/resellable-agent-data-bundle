<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Setting::create([
            "whatsapp_link" => null,
            "whatsapp_number" => null,
            "contact_number" => null,
            "account_balance" => 0.00
        ]);
        User::create([
            "name" => "Mawuli Deku",
            "phone" => "0559160090",
            "password" => Hash::make("0244123321"),
            "role" => "admin",
            "code" => Str::uuid()
        ]);


        User::create([
            "name" => "Bright Gangne",
            "phone" => "0244123321",
            "password" => Hash::make("0244123321"),
            "role" => "agent",
            "code" => Str::uuid()
        ]);

    }
}
