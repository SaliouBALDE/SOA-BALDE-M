<?php
    function loginUser($email, $password) {
        // API endpoint for user login
        $apiUrl = 'http://localhost:3500/users/login';

        // Data to send in the POST request
        $data = [
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
    $email = 'client42@geobios.com';  // Replace with the user's email
    $password = 'tester'; // Replace with the user's password

    $response = loginUser($email, $password);

    if ($response && isset($response['token'])) {
        echo "Connexion réussie !\n";
        echo "Token : " . $response['token'] . "\n";
    } else {
        echo "Erreur : Impossible de se connecter.\n";
        if ($response) {
            echo "Message d'erreur : " . json_encode($response) . "\n";
        }
    }
?>
