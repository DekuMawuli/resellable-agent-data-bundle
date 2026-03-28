<?php
namespace App\Http\Customs;

use App\Models\User;
use App\Models\SmsTracker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CustomHelper{
    public static function message($at, $am): void
    {
        $text = is_string($am) ? trim(strip_tags($am)) : (string) $am;
        if (strlen($text) > 500) {
            $text = substr($text, 0, 497) . "...";
        }
        session()->flash("at", $at);
        session()->flash("am", $text);
    }

    public static function extractDigits($string)
    {
        return preg_replace('/\D/', '', $string);
    }


    public static function generateRef(int $length = 10): string
{
    // Define the character set (alphanumeric)
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);

    $referenceNumber = '';

    // Generate random characters
    for ($i = 0; $i < $length; $i++) {
        $referenceNumber .= $characters[rand(0, $charactersLength - 1)];
    }

    return $referenceNumber;
}


// public static function sendSms(User $newUser): bool
//     {
//         $apiKey = config('services.sms.key');
//         $senderId = config('services.sms.sender_id');
//         $url = 'https://sms.kologsoft.com/sms/api';

//         $msg = "Welcome to the GloVans family. Kindly Login, Top-up and start buying the realest deals.Join our family: https://chat.whatsapp.com/ES0M5BKUJWT5LXidv607sV?mode=r_t";
//         $response = Http::get($url, [
//             'action' => 'send-sms',
//             'api_key' => $apiKey,
//             'to' => $newUser->phone,
//             'from' => $senderId,
//             'sms' => $msg,
//         ]);

//         $smsTracker = SmsTracker::first();
//         $adminUser = User::where('email', 'azumahnbalino1@gmail.com')->first();

//         if ($response->successful()) {
//             $smsTracker->update([
//                 'balance' => $smsTracker->balance - 1,
//             ]);
//             $adminUser->notify(new \App\Notifications\NewUserRegisteredNotification($newUser));
//             return true;
//         }

//         return false;
//     }



    public static function createOrder($order, $agentName){
        $order["agent_name"] = $agentName;
        DB::connection("realer")->table("agent_orders")->insert($order);
    }


    public static function updateAgentBalance($agentName, $balance){
        DB::connection("realer")->table("agent_profiles")->where("name", $agentName)->update(["balance" => $balance]);
    }
}


