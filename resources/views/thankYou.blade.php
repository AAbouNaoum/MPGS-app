<!-- resources/views/thankYou.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
</head>
<body>
    <h1>Thank You for Your Purchase!</h1>

    <p>Order ID: {{ $orderDetails['id'] }}</p>
    <p>Amount: {{ $orderDetails['amount'] }}</p>
    <p>Currency: {{ $orderDetails['currency'] }}</p>
    <p>Description: {{ $orderDetails['description'] }}</p>
    <p>Payment Status: {{ $orderDetails['status'] }}</p>

    @foreach($orderDetails['transaction'] as $transaction)
        <hr>
        <p>Transaction ID: {{ $transaction['transaction']['id'] }}</p>
        <p>Transaction Amount: {{ $transaction['transaction']['amount'] }}</p>
        <p>Transaction Status: {{ $transaction['result'] }}</p>
    @endforeach 

</body>
</html>
