<?php
function callApi($endpoint, $method = 'GET', $data = null) {
    $apiUrl = 'http://localhost:3500';

    $url = "$apiUrl/$endpoint";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch) . "\n";
        curl_close($ch);
        return null;
    } 

    curl_close($ch);

    return json_decode($response, true);
}

function loginUser($email, $password) {
    $data = [
        'email' => $email,
        'password' => $password
    ];

    $response = callApi('login', 'POST', $data);

    if ($response) {
        echo "Login successful\n";
    } else {
        echo "Error: Unable to connect.\n";
        return null;
    }
}

$email = "client42@geobios.com"; 
$password = "tester"; // Remplacez par un mot de passe valide
$token = loginUser($email, $password);
?>