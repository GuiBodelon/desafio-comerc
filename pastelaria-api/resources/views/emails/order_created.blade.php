<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Criado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4CAF50;
            text-align: center;
        }
        p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #f9f9f9;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pedido Criado com Sucesso!</h1>
        <p>Olá, {{ $order->customer->name }}!</p>
        <p>Seu pedido foi criado com sucesso. Seguem os detalhes do pedido:</p>

        <p><strong>Data do Pedido:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>

        <ul>
            @foreach ($order->products as $product)
                <li>
                    <strong>{{ $product->name }}</strong><br>
                    Quantidade: {{ $product->pivot->quantity }}<br>
                    Preço Unitário: R$ {{ number_format($product->price, 2, ',', '.') }}<br>
                    <strong>Total do Produto:</strong> R$ {{ number_format($product->pivot->quantity * $product->price, 2, ',', '.') }}
                </li>
            @endforeach
        </ul>

        <p class="total">Total do Pedido: R$ {{ number_format($order->total, 2, ',', '.') }}</p>

        <p>Obrigado por comprar conosco! Estamos preparando seu pedido.</p>

        <div class="footer">
            <p>Atenciosamente, a equipe da Pastelaria.</p>
        </div>
    </div>
</body>
</html>
