<?php

// Array com os IDs de categorias que você deseja passar
$category_ids = ['MLB5672', 'MLB271599', 'MLB1403']; 

$result = []; // Array para armazenar os resultados

foreach ($category_ids as $category_id) {
    $url = 'https://api.mercadolibre.com/categories/' . $category_id;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER => false // Desabilita a verificação SSL
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        $result[] = [
            'category_id' => $category_id,
            'error' => 'Erro na requisição: ' . curl_error($curl),
        ];
    } else {
        // Decodifica o JSON da resposta
        $data = json_decode($response, true);
        
        // Adiciona os dados ao resultado
        if (isset($data['id']) && isset($data['name'])) {
            $result[] = [
                'id' => $data['id'],
                'name' => $data['name'],
            ];
        } else {
            $result[] = [
                'category_id' => $category_id,
                'error' => 'Dados não encontrados.',
            ];
        }
    }

    curl_close($curl);
}

// Define o cabeçalho como JSON
header('Content-Type: application/json');
// Exibe o resultado em JSON
echo json_encode($result);
