<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
</head>
<body>
    <h1>Thank You for Your Payment!</h1>
    <p>Your payment for {{ $payment->childInfo->child_name }} has been successfully processed.</p>
    <p>Details:</p>
    <ul>
        <li>Reference ID: {{ $payment->payment_id }}</li>
        <li>Total Amount: RM{{ number_format($payment->total_amount, 2) }}</li>
        <li>Status: {{ ucfirst($payment->status) }}</li>
        <li>Date: {{ $payment->created_at }}</li>
    </ul>
    <p>If you have any questions, please contact our customer service.</p>
</body>
</html>
