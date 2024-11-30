<?php

require_once '../vendor/autoload.php';

$config = include('../config.php');

$chip = new \Chip\ChipApi($config['brand_id'], $config['api_key'], $config['endpoint']);

$client = new \Chip\Model\ClientDetails();
$client->email = 'test@example.com';
$purchase = new \Chip\Model\Purchase();
$purchase->client = $client;
$details = new \Chip\Model\PurchaseDetails();
$product = new \Chip\Model\Product();
$product->name = 'Test';
$product->price = 100;
$details->products = [$product];
$purchase->purchase = $details;
$purchase->brand_id = $config['brand_id'];
$purchase->success_redirect = $config['basedUrl'] . '/api/redirect.php?success=1';
$purchase->failure_redirect = $config['basedUrl'] . '/api/redirect.php?success=0';
$purchase->success_callback = $config['basedUrl'] . '/api/callback.php';

$result = $chip->createPurchase($purchase);

if ($result && $result->checkout_url) {
	// Redirect user to checkout
	header("Location: " . $result->checkout_url);
	exit;
}





// Route::post('/api/callback.php', function (Request $request) {
//     $signature = $request->header('X-Signature');
//     $content = $request->getContent();
	
// $response = Http::withHeaders([
//     'Authorization' => "Bearer " . env('CHIP_API_KEY')
// ])->get("https://gate.chip-in.asia/api/v1/public_key/");

// if ($response->failed()) {
//     Log::error("Failed to fetch public key. Status: " . $response->status());
//     Log::error("Response Body: " . $response->body());
//     return response()->json(['error' => 'Public key fetch failed'], 500);
// }

//     // Format the public key
//     $response_body = strval($response->body());
//     $response_arr = explode(PHP_EOL, $response_body);
//     array_shift($response_arr);
//     array_pop($response_arr);
//     $pub_key = "-----BEGIN PUBLIC KEY-----\n" . implode("\n", $response_arr) . "\n-----END PUBLIC KEY-----\n";

//     // Verify signature
//     $is_verified = \Chip\ChipApi::verify($content, $signature, $pub_key);

//     if (!$is_verified) {
//         Log::warning("CALLBACK: X-Signature Mismatch");
//         return response()->json(['error' => 'X-Signature Mismatch'], 400);
//     }

//     // Update order status
//     Log::info("CALLBACK: Transaction ID $request->id verification successful");
//     $payment = Payment::where('payment_id', $request->id)->first();
//     if ($payment) {
//         $payment->status = $request->status;
//         $payment->save();
//     }

//     Log::info("CALLBACK: X-Signature Verified!");
//     return response()->json(['status' => 'CALLBACK: OK']);
// });

// Route::post('/webhook/payment', function (Request $request) {
//     $signature = $request->header('X-Signature');
//     $content = $request->getContent();
//     $event = $request->input('event_type');
//     $pub_key = env('CHIP_WEBHOOK_PUBLIC_KEY');

//     $is_verified = \Chip\ChipApi::verify($content, $signature, $pub_key);

//     if (!$is_verified) {
//         Log::warning("WEBHOOK: X-Signature Mismatch");

//         return response()->json([
//             'error' => 'X-Signature Mismatch'
//         ], 400);
//     }

//     // Upon successfull verification, update transaction status in database if necessary
//     // Update order & transaction status to whatever the $event is
//     Log::info("WEBHOOK: $request->id");
//     $payment = Payment::where('payment_id', $request->id)->first();
//     if ($payment) {
//         $payment->status = $event;
//         $payment->save();
//     }

//     Log::info("WEBHOOK: X-Signature Ok!");
//     return response()->json([
//         'status' => 'WEBHOOK: OK',
//     ]);
// });