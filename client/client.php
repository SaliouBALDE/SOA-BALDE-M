<?php
    require_once("Functionnality.php");

    

    function displayMenu() {
        echo " ======== CLIENT MENU ======= \n";
        echo "\t1. SIGNUP\n";
        echo "\t2. LOGIN\n";
        echo "\t3. GET PRODUCTS\n";
        echo "\t4. GET SERVICES\n";
        echo "\t5. MAKE ORDER\n";
        echo "\t0. EXIT\n";
    }
    $baseUrl = 'http://localhost:3500';
    $functionalities = new Functionnality($baseUrl);

    do {
        displayMenu();
        $choice = (int) readline('Make a choice:');

        switch ($choice) {
            case '1':echo "----------SIGNUP------------\n";
                $roles = ['Client' => 2001];
                $email = readline("User's email: ");
                $password = readline("User's password: ");
    
                $response = $functionalities->signupUser($roles, $email, $password);
    
                if ($response && isset($response['message'])) {
                    echo "Message : " . $response['message'] . "\n";
                } else {
                    echo "Erreur : Unable to register.\n";
                    if ($response) {
                        echo "Error message : " . json_encode($response) . "\n";
                    }
                }
                break;

            case '2':echo "----------LOGIN-------------\n";
                $email = (string) readline('Enter your email (abcd@test.com): ');
                $password = (string) readline('Enter your password: '); 
    
                $response = $functionalities->loginUser($email, $password);
    
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

            case '3':echo "----------PRODUCTS----------\n";
                $token = (string) readline('Enter the token:');

                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                    exit;
                } else {

                    $products = $functionalities->getAllProducts                                                         ($token);
                   
                    if ($products) {
                        echo "List of Products:\n";
                        foreach ($products as $product) {
                            $id = $product['_id'] ?? 'Unknown Id';
                            $name = $product['name'] ?? 'Unknown Name';
                            $description = $product['description'] ?? 'Unknown Description';
                            $price = $product['price'] ?? 'Unknown Price';
                            $stock = $product['stock'] ?? 'Unknown Stock';
                    
                            echo "Id: {$id}, Name: {$name}, Description: {$description}, Price: {$price} €, Stock: {$stock}\n";
                        }
                    } else {
                        echo "Error: Unable to fetch products.\n";
                    }
                }

                
                break;

            case '4':echo "----------SERVICES----------\n";
                $token = (string) readline('Enter the token:');

                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                    exit;
                } else {
                    // Fetch all services
                    $services = $functionalities->getAllServices($token);
    
                    if ($services) {
                        echo "List of Services:\n";
                        foreach ($services as $service) {
                            $id = $service['_id'] ?? 'Unknown ID';
                            $name = $service['name'] ?? 'Unknown Name';
                            $description = $service['description'] ?? 'No Description';
                            $rate = $service['rate'] ?? 'Unknown Price';
                    
                            echo "ID: {$id}, Name: {$name}, Description: {$description}, Rate: {$rate} €\n";
                        }
                    } else {
                        echo "Error: Unable to fetch services.\n";
                    }
                }   
                
                break;

            case '5':echo "----------MAKE ORDER--------\n";
        
                // Replace this with your actual order details
                $orderDetails = [
                    'user' => [
                        '_id' => '678530cc6f38fa917f70d22f', // Replace with the user's ID
                        'email' => 'client001@geobios.com'
                    ],
                    'items' => [
                        [
                            'type' => 'Product',
                            'item' => '678542ca6f38fa917f70d257', // Replace with the product ID
                            'quantity' => 1
                        ],
                        [
                            'type' => 'Service',
                            'item' => '6785348b6f38fa917f70d235', // Replace with the service ID
                            'quantity' => 1
                        ]
                    ]
                ];
                $token = (string) readline('Enter the token:');

                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                    exit;
                } else {
                    $orderResponse = $functionalities->createOrder($token, $orderDetails);
        
                    if ($orderResponse) {
                        print_r($orderResponse);
                    } else {
                        echo "Error: Unable to place the order.\n";
                    }
                }               
                break;

            default: 
                echo "Invalid choice.\n";
        }
    }while($choice != 0);
?>