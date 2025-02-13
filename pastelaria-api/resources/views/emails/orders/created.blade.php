<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido Criado</title>
</head>
<body>
    <h1>Pedido Criado com Sucesso!</h1>
    <p>Olá, {{ $order->customer->name }}!</p>
    <p>Seu pedido foi criado com sucesso. Seguem os detalhes do pedido:</p>
    <ul>
        @foreach ($order->products as $product)
            <li>{{ $product->name }} - R$ {{ number_format($product->price, 2, ',', '.') }}</li>
        @endforeach
    </ul>
    <p>Obrigado por comprar conosco!</p>
</body>
</html>
