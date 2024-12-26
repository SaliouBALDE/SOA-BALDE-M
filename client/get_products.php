<?php
function getAllProducts($token) {
    // The API endpoint for fetching all products
    $apiUrl = 'http://localhost:3500/products';

    // Initialize cURL
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token // Pass the token for authentication
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
    if (isset($decodedResponse['products']) && is_array($decodedResponse['products'])) {
        return $decodedResponse['products']; // Case: Nested products key
    } elseif (is_array($decodedResponse)) {
        return $decodedResponse; // Case: Direct array of products
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

// Fetch all products
$products = getAllProducts($token);

if ($products) {
    echo "List of Products:\n";
    foreach ($products as $product) {
        $id = $product['_id'] ?? 'Unknown ID';
        $name = $product['name'] ?? 'Unknown Name';
        $price = $product['price'] ?? 'Unknown Price';

        echo "ID: {$id}, Name: {$name}, Price: {$price} €\n";
    }
} else {
    echo "Error: Unable to fetch products.\n";
}
?>
