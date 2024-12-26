<?php
    function authenticateUser($email, $password) {
        $authUrl = 'http://localhost:3500/users/login';
        $credentials = json_encode([
            'email' => $email,
            'password' => $password
        ]);

        $ch = curl_init($authUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $credentials);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch) . "\n";
            curl_close($ch);
            return null;
        }

        curl_close($ch);
        $decodedResponse = json_decode($response, true);

        return $decodedResponse['token'] ?? null;
    }

    function makeOrder($token, $orderDetails) {
        // The API endpoint for placing an order
        $apiUrl = 'http://localhost:3500/orders';

        // Initialize cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);

        // Set the order details as POST data
        $postData = json_encode($orderDetails);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        // Execute the POST request
        $response = curl_exec($ch);

        // Handle cURL errors
        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch) . "\n";
            curl_close($ch);
            return null;
        }

        // Close the cURL session
        curl_close($ch);

        // Debugging: Log raw response
        echo "Raw Response: " . $response . "\n";

        // Decode the JSON response
        $decodedResponse = json_decode($response, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error decoding JSON: " . json_last_error_msg() . "\n";
            return null;
        }

        return $decodedResponse;
    }

    // Replace these with valid credentials
    $email = 'client42@geobios.com';
    $password = 'tester';

    // Replace this with your actual order details
    $orderDetails = [
        'user' => [
            '_id' => '676b3bda00cf9806896f5545', // Replace with the user's ID
            'email' => 'client42@geobios.com'
        ],
        'items' => [
            [
                'type' => 'Product',
                'item' => '676832f53305b9363bfe2ead', // Replace with the product ID
                'quantity' => 1
            ],
            [
                'type' => 'Service',
                'item' => '676833e63305b9363bfe2eb1', // Replace with the service ID
                'quantity' => 2
            ]
        ],
        'totalAmount' => 14
    ];

    // Authenticate and get token
    $token = authenticateUser($email, $password);

    if (!$token) {
        echo "Error: Unable to authenticate.\n";
        exit;
    }

    // Place the order
    $orderResponse = makeOrder($token, $orderDetails);

    if ($orderResponse) {
        echo "Order placed successfully:\n";
        print_r($orderResponse);
    } else {
        echo "Error: Unable to place the order.\n";
    }
?>
