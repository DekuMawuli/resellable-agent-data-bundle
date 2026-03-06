<?php

namespace App\Http\Controllers;

use App\Models\TopUp;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Customs\CustomHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PayStackController extends Controller
{
    public function initPayment(Request $request){
        $request->validate([
            "amount" => "required|numeric|gt:0",
            "type" => "required"
        ]);


        // dd($request->amount);

        $sk = config("services.paystack.secret");
        // dd("Bearer $sk");
        $amount = $request->amount * 100;
        $extra_charges = $amount * 0.02;
        $total_amount = $amount + $extra_charges;
        $email =  "user_".uniqid(5)."@gmail.com";
        $response = Http::withHeaders([
            'Authorization' => "Bearer $sk" ,
            'Cache-Control' => 'no-cache',
            'Accept' => 'application/json',
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => $email,
            'amount' => (int) $total_amount,
            "metadata" => json_encode([
                "user_id" => $request->user()->id,
                "user_email" => $email,
                "user_name" => $request->user()->name,
                "type" => $request->type,
            ]),
            "callback_url" => route('agent.handle-payment-callback')
        ]);





        if ($response->successful()) {
            $data = $response->json();

            $amount = $request->amount;

            if ($data["status"]){
                session()->put("reference", $data["data"]["reference"]);
                Transaction::create([
                    "customer_id" => $request->user()->id,
                    "code" => $data["data"]["reference"],
                    "amount" => $amount,
                    "type" => "{$request->type}",
                    "status" => "pending",

                ]);
                return redirect()->to($data["data"]["authorization_url"]);
            }
        }

        $data = $response->json();




        CustomHelper::message("danger", $response["message"]);
        return back();
    }



    public function handlePaymentCallback(Request $request){


        $reference = $request->query('reference');

        if (!$reference) {
CustomHelper::message("danger", "An error occurred while trying to process your payment. Please try again.");
            return redirect()->route("agent.dashboard");
        }

        // Call Paystack's verify API
        $sk = config("services.paystack.secret");
        $response = Http::withHeaders([
            'Authorization' => "Bearer $sk",
            'Cache-Control' => 'no-cache',
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        // Decode the response
        $paymentDetails = $response->json();



        // Check if the request was successful
        if ($response->successful() && $paymentDetails['data']['status'] === 'success') {
            // TODO: Save transaction details in the database

            $transaction = Transaction::where("code", $reference)->get();

            if (count($transaction) == 1){
                $transaction = $transaction->first();
                if ($paymentDetails['data']['status'] == 'success'){
                        TopUp::create([
                            "code" => $reference,
                            "customer_id" => auth()->id(),
                            "amount" => $transaction->amount,
                            "payment_made" => true,
                            "status" => "completed"
                        ]);


                        auth()->user()->balance += $transaction->amount;
                        auth()->user()->save();

                        $actualBalance = $transaction->amount;

                        $transaction->status = "completed";
                        $transaction->save();

                        CustomHelper::message("success", "Payment of {$actualBalance} was successful");

                        return redirect()->route("agent.dashboard");
                }
                return redirect()->route("agent.dashboard");
            }
            elseif ($paymentDetails['data']['status'] == 'failed'){
                CustomHelper::message("danger", $paymentDetails["data"]["gateway_response"]);
                return redirect()->route("agent.dashboard");
            }
            elseif ($paymentDetails['data']['status'] == 'pending'){{
                CustomHelper::message("info", $paymentDetails["data"]["gateway_response"]);
                return redirect()->route("agent.dashboard");
            }






        }
    }


}

public function verifyPayment(Request $request)
    {
        $request->validate([
            'reference' => 'required|string',
        ]);

        $reference = $request->reference;

        try {
            // Find the local transaction record FIRST.
            // Note: I've changed from `where('type', 'LIKE', ...)` to a more precise and efficient query.
            // Please ensure you have a 'reference' column on your transactions table.
            $transaction = Transaction::where('code', $reference)->first();

            if (!$transaction) {
                // This is a critical issue. A reference is being verified that our system doesn't know about.
                Log::error("Paystack verification attempt for an unknown reference: {$reference}");
                CustomHelper::message('danger', 'Transaction reference not found in our system.');
                return redirect()->route('agent.dashboard');
            }

            $topUp = TopUp::where('code', $reference)->first();
            if ($topUp && $topUp->status === 'completed') {
                CustomHelper::message('info', 'This transaction has already been processed and credited.');
                return redirect()->route('agent.dashboard');
            }

            if ($transaction->status == null){
                CustomHelper::message('info', 'This transaction does not exist.');
                return redirect()->route('agent.dashboard');
            }

            if ($transaction->status === 'completed') {
                CustomHelper::message('info', 'This transaction has already been processed and credited.');
                return redirect()->route('agent.dashboard');
            }

            // Prevent re-processing a completed transaction
            if ($transaction->status === 'completed') {
                CustomHelper::message('info', 'This transaction has already been processed and credited.');
                return redirect()->route('agent.dashboard');
            }

            // Call Paystack's verify API
            $sk = config('services.paystack.secret');
            $response = Http::withToken($sk) // A cleaner way to set the Bearer token
                ->withHeaders(['Cache-Control' => 'no-cache'])
                ->get("https://api.paystack.co/transaction/verify/{$reference}");

            $paymentDetails = $response->json();

            // Check for failed API call or invalid response from Paystack
            if (!$response->successful() || !$paymentDetails['status']) {
                Log::error('Paystack verification failed', [
                    'reference' => $reference,
                    'response' => $paymentDetails
                ]);
                CustomHelper::message('danger', 'Could not verify the transaction at this time. Please contact support.');
                return redirect()->route('agent.dashboard');
            }

            // Get the status from the 'data' array
            $status = $paymentDetails['data']['status'];
            $gatewayResponse = $paymentDetails['data']['gateway_response'];


            // Handle logic based on the transaction status
            switch ($status) {
                case 'success':
                    // Use a database transaction to ensure data integrity
                    DB::transaction(function () use ($transaction, $paymentDetails) {
                        // Create the TopUp record
                        TopUp::create([
                            "code" => $transaction->code,
                            'user_id' => $transaction->user_id, // Use ID from transaction record
                            'amount' => $transaction->amount,
                            'payment_made' => true,
                            'status' => 'completed',
                            'customer_id' => $transaction->customer_id,
                        ]);




                        // Credit the user's balance
                        $user = $transaction->customer; //
                        $user->balance += $transaction->amount;
                        $user->save();
                        // Update the transaction status
                        $transaction->status = 'completed';
                        $transaction->save();
                    });

                    CustomHelper::message('success', "Payment of {$transaction->amount} was successful and your wallet has been credited.");
                    return redirect()->route('agent.dashboard');

                case 'failed':
                    CustomHelper::message('danger', "Transaction Failed: {$gatewayResponse}");
                    return redirect()->route('agent.dashboard');

                case 'abandoned':
                    CustomHelper::message('warning', 'You did not complete the transaction. It has been marked as abandoned.');
                    return redirect()->route('agent.dashboard');

                case 'reversed':
                    // This is an important case. If the money was previously credited, you need to reverse it.
                    // This logic assumes you might need to handle a reversal later on.
                    // For now, we just notify the user.
                    CustomHelper::message('info', 'This transaction has been reversed.');
                    return redirect()->route('agent.dashboard');

                // These are all "in-progress" states. We treat them the same: inform the user to wait.
                case 'ongoing':
                case 'pending':
                case 'processing':
                case 'queued':
                    CustomHelper::message('info', "Your transaction is still being processed. Its status is '{$status}'. Please check back in a few minutes.");
                    return redirect()->route('agent.dashboard');

                default:
                    // Handle any unexpected status
                    Log::warning("Paystack verification returned an unhandled status: {$status}", ['reference' => $reference]);
                    CustomHelper::message('info', "Transaction status is currently '{$status}'. Please check again later or contact support.");
                    return redirect()->route('agent.dashboard');
            }

        } catch (\Exception $e) {
            Log::error("An exception occurred during Paystack verification for reference: {$reference}", [
                'error' => $e->getMessage()
            ]);

            dd($e->getMessage());
            CustomHelper::message('danger', 'A system error occurred. Please contact support.');
            return redirect()->route('agent.dashboard');
        }
    }
}
