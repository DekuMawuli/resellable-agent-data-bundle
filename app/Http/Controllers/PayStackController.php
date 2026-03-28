<?php

namespace App\Http\Controllers;

use App\Models\TopUp;
use App\Models\Transaction;
use App\Models\Setting;
use App\Support\PaystackCredentials;
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


        $useLive = (bool) Setting::query()->value("use_live_payment");
        $sk = PaystackCredentials::secretForMode($useLive);
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
                    "paystack_live_mode" => $useLive,
                ]);
                Log::channel("paystack")->info("Paystack initialize succeeded", [
                    "user_id" => $request->user()->id,
                    "amount" => $amount,
                    "type" => $request->type,
                    "live_mode" => $useLive,
                    "reference" => $data["data"]["reference"] ?? null,
                ]);
                return redirect()->to($data["data"]["authorization_url"]);
            }
        }

        $json = $response->json();
        $msg = is_array($json) ? ($json["message"] ?? "Payment initialization failed.") : "Payment initialization failed.";
        Log::channel("paystack")->warning("Paystack initialize failed", [
            "user_id" => $request->user()->id,
            "http_status" => $response->status(),
            "live_mode" => $useLive,
            "message" => is_string($msg) ? $msg : null,
        ]);
        CustomHelper::message("danger", is_string($msg) ? $msg : "Payment initialization failed.");
        return back();
    }



    public function handlePaymentCallback(Request $request){


        $reference = $request->query('reference');

        if (!$reference) {
            Log::channel("paystack")->warning("Paystack callback missing reference query param");
            CustomHelper::message("danger", "An error occurred while trying to process your payment. Please try again.");
            return redirect()->route("agent.dashboard");
        }

        $existing = Transaction::query()->where("code", $reference)->first();
        $useLive = $existing && $existing->paystack_live_mode !== null
            ? (bool) $existing->paystack_live_mode
            : (bool) Setting::query()->value("use_live_payment");
        $sk = PaystackCredentials::secretForMode($useLive);
        $response = Http::withHeaders([
            'Authorization' => "Bearer $sk",
            'Cache-Control' => 'no-cache',
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        $paymentDetails = $response->json();
        $gatewayStatus = is_array($paymentDetails) && isset($paymentDetails["data"]["status"])
            ? $paymentDetails["data"]["status"]
            : null;
        Log::channel("paystack")->info("Paystack callback verify response", [
            "reference" => $reference,
            "http_status" => $response->status(),
            "gateway_status" => $gatewayStatus,
            "live_mode" => $useLive,
        ]);

        if ($response->successful() && is_array($paymentDetails) && ($paymentDetails["data"]["status"] ?? null) === "success") {
            // TODO: Save transaction details in the database

            $transaction = Transaction::where("code", $reference)->get();

            if (count($transaction) == 1){
                $transaction = $transaction->first();
                if (($paymentDetails["data"]["status"] ?? null) == "success") {
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
                        Log::channel("paystack")->info("Paystack callback wallet credited", [
                            "reference" => $reference,
                            "user_id" => auth()->id(),
                            "amount" => (float) $actualBalance,
                        ]);

                        return redirect()->route("agent.dashboard");
                }
                return redirect()->route("agent.dashboard");
            }
            elseif (($paymentDetails["data"]["status"] ?? null) == "failed") {
                CustomHelper::message("danger", $paymentDetails["data"]["gateway_response"] ?? "");
                return redirect()->route("agent.dashboard");
            }
            elseif (($paymentDetails["data"]["status"] ?? null) == "pending") {
                CustomHelper::message("info", $paymentDetails["data"]["gateway_response"] ?? "");
                return redirect()->route("agent.dashboard");
            }
        }

        return redirect()->route("agent.dashboard");
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
                Log::channel("paystack")->error("Paystack verify: unknown reference", ["reference" => $reference]);
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

            $useLive = $transaction->paystack_live_mode !== null
                ? (bool) $transaction->paystack_live_mode
                : (bool) Setting::query()->value("use_live_payment");
            $sk = PaystackCredentials::secretForMode($useLive);
            $response = Http::withToken($sk)
                ->withHeaders(['Cache-Control' => 'no-cache'])
                ->get("https://api.paystack.co/transaction/verify/{$reference}");

            $paymentDetails = $response->json();

            // Check for failed API call or invalid response from Paystack
            if (!$response->successful() || !$paymentDetails['status']) {
                Log::channel("paystack")->error("Paystack verify API failed", [
                    "reference" => $reference,
                    "http_status" => $response->status(),
                    "paystack_ok" => $paymentDetails["status"] ?? null,
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
                    Log::channel("paystack")->info("Paystack verify POST wallet credited", [
                        "reference" => $reference,
                        "customer_id" => $transaction->customer_id,
                        "amount" => (float) $transaction->amount,
                    ]);
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
                    Log::channel("paystack")->warning("Paystack verify unhandled status", [
                        "reference" => $reference,
                        "status" => $status,
                    ]);
                    CustomHelper::message('info', "Transaction status is currently '{$status}'. Please check again later or contact support.");
                    return redirect()->route('agent.dashboard');
            }

        } catch (\Exception $e) {
            Log::channel("paystack")->error("Paystack verify exception", [
                "reference" => $reference,
                "message" => $e->getMessage(),
            ]);

            CustomHelper::message('danger', 'A system error occurred. Please contact support.');
            return redirect()->route('agent.dashboard');
        }
    }
}
