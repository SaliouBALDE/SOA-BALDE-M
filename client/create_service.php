<?php
    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI2NzY3MjAwYmI5YTJjYzAxNmU5M2EyMWIiLCJyb2xlcyI6eyJDbGllbnQiOjIwMDEsIkVtcGxveWVlIjoxOTg0fSwiZW1haWwiOiJlbXBsb3llZTFAZ2VvYmlvcy5jb20iLCJleHAiOiIxNzM1MjI2NTk3W29iamVjdCBPYmplY3RdIn0.vP11Wm7_Y0f_xkcdWbCUW76t2ViwA2MsB0Q-zyUKJW4";

    function createService($token, $serviceDetails) {
        $apiUrl = 'http://localhost:3500/services';

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);

        $postData = json_encode($serviceDetails);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

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

    // Replace this with your actual service details
    $serviceDetails = [
        'name' => 'Premium Cleaning Service',
        'description' => 'High-quality cleaning service for homes and offices.',
        'price' => 120.00,
        'availableEmployees' => [
            '67674ca5b5848fe8c43f7e97', // Replace with actual employee IDs
            '67674ca5b5848fe8c43f7e98'
        ]
    ];

    if (!$token) {
        echo "Error: Unable to authenticate.\n";
        exit;
    }

    $serviceResponse = createService($token, $serviceDetails);

    if ($serviceResponse) {
        echo "Service created successfully:\n";
        print_r($serviceResponse);
    } else {
        echo "Error: Unable to create the service.\n";
    }
?>
