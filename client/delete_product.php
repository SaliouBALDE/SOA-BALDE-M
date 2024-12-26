<?php
    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI2NzY3MjAwYmI5YTJjYzAxNmU5M2EyMWIiLCJyb2xlcyI6eyJDbGllbnQiOjIwMDEsIkVtcGxveWVlIjoxOTg0fSwiZW1haWwiOiJlbXBsb3llZTFAZ2VvYmlvcy5jb20iLCJleHAiOiIxNzM1MjI2NTk3W29iamVjdCBPYmplY3RdIn0.vP11Wm7_Y0f_xkcdWbCUW76t2ViwA2MsB0Q-zyUKJW4";


    function deleteProduct($token, $productId) {
        $apiUrl = "http://localhost:3500/products/$productId";

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
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

    // Replace with the product ID to delete
    $productId = '676d753bfd019c874636f08d';

    if (!$token) {
        echo "Error: Unable to authenticate.\n";
        exit;
    }

    // Delete the product
    $deleteResponse = deleteProduct($token, $productId);

    if ($deleteResponse && isset($deleteResponse['message'])) {
        echo "Response: " . $deleteResponse['message'] . "\n";
    } else {
        echo "Error: Unable to delete the product.\n";
    }
?>
