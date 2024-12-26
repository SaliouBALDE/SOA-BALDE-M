<?php
function getAllServices($token) {
    // The API endpoint for fetching all services
    $apiUrl = 'http://localhost:3500/services';

    // Initialize cURL
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);

    // Execute the GET request
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

    // Handle response structure (modify as needed)
    if (isset($decodedResponse['services']) && is_array($decodedResponse['services'])) {
        return $decodedResponse['services']; // Case: Nested services key
    } elseif (is_array($decodedResponse)) {
        return $decodedResponse; // Case: Direct array of services
    }

    // If neither case matches, return null
    echo "Unexpected response format.\n";
    return null;
}

// Authenticate and get token
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

// Replace these with valid credentials
$email = 'client42@geobios.com';
$password = 'tester';

// Get the token
$token = authenticateUser($email, $password);

if (!$token) {
    echo "Error: Unable to authenticate.\n";
    exit;
}

// Fetch all services
$services = getAllServices($token);

if ($services) {
    echo "List of Services:\n";
    foreach ($services as $service) {
        $id = $service['_id'] ?? 'Unknown ID';
        $name = $service['name'] ?? 'Unknown Name';
        $description = $service['description'] ?? 'No Description';
        $price = $service['price'] ?? 'Unknown Price';

        echo "ID: {$id}, Name: {$name}, Description: {$description}, Price: {$price} €\n";
    }
} else {
    echo "Error: Unable to fetch services.\n";
}
?>
