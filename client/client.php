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

        // Decode the JSON response
        $decodedResponse = json_decode($response, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error decoding JSON: " . json_last_error_msg() . "\n";
            return null;
        }

        return $decodedResponse;
    }

    function displayMenu() {
        echo " ======== CLIENT MENU ======= \n";
        echo "\t1. SIGNUP\n";
        echo "\t2. LOGIN\n";
        echo "\t3. GET PRODUCTS\n";
        echo "\t4. GET SERVICES\n";
        echo "\t5. MAKE ORDER\n";
        echo "\t6. EXIT\n";
    
        echo "\n Make a choice:\t";
    }

    do {
        displayMenu();
        $choice = trim(fgetc(STDIN));

        switch ($choice) {
            case '1':
                echo "----------SIGNUP------------\n";
                // Example usage
                $roles = ['Client' => 2001];
                $email = 'client99@geobios.com'; 
                $password = 'tester';
    
                $response = signupUser($roles, $email, $password);
    
                if ($response && isset($response['message'])) {
                    echo "Message : " . $response['message'] . "\n";
                } else {
                    echo "Erreur : Unable to register.\n";
                    if ($response) {
                        echo "Error message : " . json_encode($response) . "\n";
                    }
                }
                break;

            case '2': 
                echo "----------LOGIN------------\n";
                $email = 'client42@geobios.com';
                $password = 'tester'; 
    
                $response = loginUser($email, $password);
    
                if ($response && isset($response['token'])) {
                    echo "Connection successful !\n";
                    echo "Token : " . $response['token'] . "\n";
                } else {
                    echo "Error : Unable to connect.\n";
                    if ($response) {
                        echo "Error message : " . json_encode($response) . "\n";
                    }
                }
    
                break;

            case '3':
                echo "----------PRODUCTS------------\n";
                // Replace these with valid credentials
                $email = 'client42@geobios.com';
                $password = 'tester';

                $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI2NzZiM2JkYTAwY2Y5ODA2ODk2ZjU1NDUiLCJyb2xlcyI6eyJDbGllbnQiOjIwMDF9LCJlbWFpbCI6ImNsaWVudDQyQGdlb2Jpb3MuY29tIiwiZXhwIjoiMTczNTE3MTc1NltvYmplY3QgT2JqZWN0XSJ9.wZZmsOVm_uwCGZIwVj67eIRgOlOlxQkVQWXqqNpTOaI";

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
    
                break;

            case '4':
                echo "----------SERVICES------------\n";
    
                // Get the token
                $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI2NzZiM2JkYTAwY2Y5ODA2ODk2ZjU1NDUiLCJyb2xlcyI6eyJDbGllbnQiOjIwMDF9LCJlbWFpbCI6ImNsaWVudDQyQGdlb2Jpb3MuY29tIiwiZXhwIjoiMTczNTE3MTc1NltvYmplY3QgT2JqZWN0XSJ9.wZZmsOVm_uwCGZIwVj67eIRgOlOlxQkVQWXqqNpTOaI";

    
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
                break;

            case '5':
                echo "----------MAKE ORDER------------\n";
        
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

                $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI2NzZiM2JkYTAwY2Y5ODA2ODk2ZjU1NDUiLCJyb2xlcyI6eyJDbGllbnQiOjIwMDF9LCJlbWFpbCI6ImNsaWVudDQyQGdlb2Jpb3MuY29tIiwiZXhwIjoiMTczNTE3MTc1NltvYmplY3QgT2JqZWN0XSJ9.wZZmsOVm_uwCGZIwVj67eIRgOlOlxQkVQWXqqNpTOaI";

    
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
                break;

            default: 
                echo "Invalid choice.\n";
        }
    }while($choice != 6);
?>