<?php

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/callback', function (Request $request) {
    $signature = $request->header('X-Signature');
    $content = $request->getContent();

    // Fetch public key
    $response = Http::withHeaders([
        'Authorization' => "Bearer " . env('CHIP_API_KEY')
    ])->get("https://gate.chip-in.asia/api/v1/public_key/");

    if ($response->failed()) {
        Log::error("Failed to fetch public key");
        return response()->json(['error' => 'Public key fetch failed'], 500);
    }

    // Format the public key
    $response_body = strval($response->body());
    $response_arr = explode(PHP_EOL, $response_body);
    array_shift($response_arr);
    array_pop($response_arr);
    $pub_key = "-----BEGIN PUBLIC KEY-----\n" . implode("\n", $response_arr) . "\n-----END PUBLIC KEY-----\n";

    // Verify signature
    $is_verified = \Chip\ChipApi::verify($content, $signature, $pub_key);

    if (!$is_verified) {
        Log::warning("CALLBACK: X-Signature Mismatch");
        return response()->json(['error' => 'X-Signature Mismatch'], 400);
    }

    // Update order status
    Log::info("CALLBACK: Transaction ID $request->id verification successful");
    $payment = Payment::where('payment_id', $request->id)->first();
    if ($payment) {
        $payment->status = $request->status;
        $payment->save();
    }

    Log::info("CALLBACK: X-Signature Verified!");
    return response()->json(['status' => 'CALLBACK: OK']);
});

Route::post('/webhook/payment', function (Request $request) {
    $signature = $request->header('X-Signature');
    $content = $request->getContent();
    $event = $request->input('event_type');
    $pub_key = env('CHIP_WEBHOOK_PUBLIC_KEY');

    $is_verified = \Chip\ChipApi::verify($content, $signature, $pub_key);

    if (!$is_verified) {
        Log::warning("WEBHOOK: X-Signature Mismatch");

        return response()->json([
            'error' => 'X-Signature Mismatch'
        ], 400);
    }

    // Upon successfull verification, update transaction status in database if necessary
    // Update order & transaction status to whatever the $event is
    Log::info("WEBHOOK: $request->id");
    $payment = Payment::where('payment_id', $request->id)->first();
    if ($payment) {
        $payment->status = $event;
        $payment->save();
    }

    Log::info("WEBHOOK: X-Signature Ok!");
    return response()->json([
        'status' => 'WEBHOOK: OK',
    ]);
});
