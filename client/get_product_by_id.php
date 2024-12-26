<?php
    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI2NzY3MjAwYmI5YTJjYzAxNmU5M2EyMWIiLCJyb2xlcyI6eyJDbGllbnQiOjIwMDEsIkVtcGxveWVlIjoxOTg0fSwiZW1haWwiOiJlbXBsb3llZTFAZ2VvYmlvcy5jb20iLCJleHAiOiIxNzM1MjI2NTk3W29iamVjdCBPYmplY3RdIn0.vP11Wm7_Y0f_xkcdWbCUW76t2ViwA2MsB0Q-zyUKJW4";

    function getProductById($token, $productId) {
        $apiUrl = "http://localhost:3500/products/$productId";

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch) . "\n";
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error decoding JSON: " . json_last_error_msg() . "\n";
            return null;
        }

        return $decodedResponse;
    }

    $productId = '676832f53305b9363bfe2ead';

    if (!$token) {
        echo "Error: Unable to authenticate.\n";
        exit;
    }

    $productResponse = getProductById($token, $productId);

    if ($productResponse && isset($productResponse['product'])) {
        $product = $productResponse['product'];
        echo "Product Details:\n";
        echo "ID: {$product['_id']}\n";
        echo "Name: {$product['name']}\n";
        echo "Description: {$product['description']}\n";
        echo "Price: {$product['price']} €\n";
        echo "Stock: {$product['stock']}\n";
    } else {
        echo "Error: Unable to fetch the product.\n";
    }
?>
