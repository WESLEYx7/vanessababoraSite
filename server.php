<?php
    $access_token = 'TEST-1190270351295516-031212-1e65affd625ff7dbde4f3fd8e26eed3e-309323339';

    // Dados do item
    $item_data = [
        'id' => 1,
        'title' => 'Pack Presets Parto',
        'quantity' => 1,
        'unit_price' => 120,
        'currency_id' => 'BRL'
    ];

    // Dados da preferência
    $preference_data = [
        'items' => [$item_data]
    ];

    // URL da API do MercadoPago para criar uma preferência
    $url = 'https://api.mercadopago.com/checkout/preferences';

    // Configuração da solicitação
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($preference_data),
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json',
        ],
    ];

    // Inicialização do cURL
    $curl = curl_init();

    // Configuração das opções
    curl_setopt_array($curl, $options);

    // Execução da solicitação
    $response = curl_exec($curl);

    // Verificação de erros
    if ($response === false) {
        die(curl_error($curl));
    }

    // Fechamento do cURL
    curl_close($curl);

    // Decodificação da resposta JSON
    $preference = json_decode($response);

    // Verificação da resposta
    if (!isset($preference->id)) {
        die('Erro ao criar preferência');
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento</title>
</head>
<body>

    <div id="wallet_container"></div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const public_key = 'TEST-b5f83d57-690b-470d-8712-b52ae3937fef';
        const mp = new MercadoPago(public_key, {
            locale: 'pt-BR'
        });

        mp.bricks().create("wallet", "wallet_container", {
            initialization: {
                preferenceId: "<?php echo $preference->id; ?>",
            },
            customization: {
                texts: {
                    valueProp: 'smart_option',
                },
            },
        });
    </script>
</body>
</html>
