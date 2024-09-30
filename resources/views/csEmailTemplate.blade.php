<!DOCTYPE html>
<html>
<head>
    <title>Email from {{ config('app.name') }}</title>
</head>
<body>
    <h1>{{ $subject }}</h1>
    <p>{{ $messageBody }}</p>
</body>
</html>
