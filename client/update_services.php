<?php
    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI2NzY3MjAwYmI5YTJjYzAxNmU5M2EyMWIiLCJyb2xlcyI6eyJDbGllbnQiOjIwMDEsIkVtcGxveWVlIjoxOTg0fSwiZW1haWwiOiJlbXBsb3llZTFAZ2VvYmlvcy5jb20iLCJleHAiOiIxNzM1MjI2NTk3W29iamVjdCBPYmplY3RdIn0.vP11Wm7_Y0f_xkcdWbCUW76t2ViwA2MsB0Q-zyUKJW4";

    function updateService($token, $serviceId, $updates) {
        $apiUrl = "http://localhost:3500/services/$serviceId";

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updates));

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


    $serviceId = '67687d89c4d85b585fe28ff3';
    $updates = [
        ["propName" => "name", "value" => "Updated Service Name"],
        ["propName" => "price", "value" => 59.99]
    ];

    if (!$token) {
        echo "Error: Unable to authenticate.\n";
        exit;
    }

    // Update the service
    $updateResponse = updateService($token, $serviceId, $updates);

    if ($updateResponse && isset($updateResponse['message'])) {
        echo "Response: " . $updateResponse['message'] . "\n";
    } else {
        echo "Error: Unable to update the service.\n";
    }
?>
