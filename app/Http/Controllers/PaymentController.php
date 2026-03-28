<?php

namespace App\Http\Controllers;

use App\Http\Customs\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaymentController extends Controller
{

    // https://webhook.site/cdadad0b-5dec-4c5c-b826-07172cde291d
    //

    public function redirectToGateway()
    {
        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            Log::channel("paystack")->error("Paystack package redirectToGateway failed", [
                "message" => $e->getMessage(),
            ]);
            CustomHelper::message("danger", 'The paystack token has expired. Please refresh the page and try again.');
            return redirect()->back();
        }
    }

    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        if ($paymentDetails['status']) {
            $amount = $paymentDetails['amount'] / 100;
            $reference = $paymentDetails['reference'];
            Log::channel("paystack")->info("Paystack package callback success", [
                "reference" => $reference,
                "amount" => $amount,
            ]);
            CustomHelper::message("success", "Payment of $amount was successful. Reference: $reference");
            return redirect()->back();
        }

        Log::channel("paystack")->warning("Paystack package callback not successful", [
            "reference" => $paymentDetails['reference'] ?? null,
        ]);
        CustomHelper::message("danger", "Payment failed");
        return redirect()->back();
    }
}
