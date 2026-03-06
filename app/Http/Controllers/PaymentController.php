<?php

namespace App\Http\Controllers;

use App\Http\Customs\CustomHelper;
use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaymentController extends Controller
{

    // https://webhook.site/cdadad0b-5dec-4c5c-b826-07172cde291d
    //

    public function redirectToGateway()
    {
        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch(\Exception $e) {
            CustomHelper::message("danger", 'The paystack token has expired. Please refresh the page and try again.');
            return redirect()->back();
        }
    }

    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        if ($paymentDetails['status']) {
            // Payment was successful
            $amount = $paymentDetails['amount'] / 100; // Convert amount from kobo to naira
            $reference = $paymentDetails['reference'];

            // Update your database here
            // For example, you might update an order status or create a new transaction record
            CustomHelper::message("success", "Payment of $amount was successful. Reference: $reference");
            return redirect()->back();
        } else {
            // Payment failed
            CustomHelper::message("danger","Payment failed");
            return redirect()->back();
        }
    }
}
