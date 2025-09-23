<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <title>Document</title>
</head>
<body>
  <h1>Order Details</h1>

  <p><strong>Customer Name:</strong> {{ $order->name }}</p>
  <p><strong>Customer Email:</strong> {{ $order->email }}</p>
  <p><strong>Customer Phone:</strong> {{ $order->phone }}</p>
  <p><strong>Customer User ID:</strong> {{ $order->user_id }}</p>
  <p><strong>Customer Address:</strong> {{ $order->addres }}</p>
  <p><strong>Product Title:</strong> {{ $order->product_title }}</p>
  <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
  <p><strong>Price:</strong> {{ $order->price }}</p>
  <p><strong>Product ID:</strong> {{ $order->product_id }}</p>
  <p><strong>Image:</strong><br><img style="width: 150px; height:auto" src="product/{{ $order->image }}"> </p>
  <p><strong>Payment Status:</strong> {{ $order->payment_status }}</p>
  <p><strong>Delivery Status:</strong> {{ $order->delivery_status }}</p>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</html>