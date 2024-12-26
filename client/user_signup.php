<?php
    function signupUser($roles, $email, $password) {
        // API endpoint for user signup
        $apiUrl = 'http://localhost:3500/users/signup';

        // Data to send in the POST request
        $data = [
            'roles' => $roles,
            'email' => $email,
            'password' => $password
        ];

        // Initialize cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        // Execute the request and get the response
        $response = curl_exec($ch);

        // Handle cURL errors
        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch) . "\n";
            curl_close($ch);
            return null;
        }

        // Close the cURL session
        curl_close($ch);

        // Decode the JSON response
        return json_decode($response, true);
    }

    // Example usage
    $roles = ['Client' => 2001];
    $email = 'client63@geobios.com'; // Replace with user's email
    $password = 'tester'; // Replace with user's password

    $response = signupUser($roles, $email, $password);

    if ($response && isset($response['message'])) {
        echo "Inscription réussie !\n";
        echo "Message : " . $response['message'] . "\n";
    } else {
        echo "Erreur : Impossible de s'inscrire.\n";
        if ($response) {
            echo "Message d'erreur : " . json_encode($response) . "\n";
        }
    }
?>
