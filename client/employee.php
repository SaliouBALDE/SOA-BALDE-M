<?php
    require_once("Functionnality.php");

    function displayMenu() {
        echo " ========= EMPLOYEE MENU ========= \n";
        echo "\t01. SIGNUP\n";
        echo "\t02. LOGIN\n";
        echo "\t03. CREATE PRODUCT\n";
        echo "\t04. GET PRODUCT BY ID\n";
        echo "\t05. UPDATE PRODUCT BY ID\n";
        echo "\t06. DELETE PRODUCT BY ID\n";
        echo "\t07. CREATE SERVICE\n";
        echo "\t08. GET SERVICE BY ID\n";
        echo "\t09. UPDATE SERVICE BY ID\n";
        echo "\t10. DELETE SERVICE BY ID\n";    
        echo "\t11. ASSIGN TO SERVICE\n";
        echo "\t12. GET ORDER BY ID\n";
        echo "\t13. DELETE ORDER BY ID\n";
        echo "\t0. EXIT\n";
    
    }

    do {
        displayMenu();
        $choice = (int) readline('Make a choice:');


        echo "Choice : $choice\n";
        $baseUrl = 'http://localhost:3500';
        $functionnalities = new Functionnality($baseUrl);
        
        switch ($choice) {
            case '1'://Employee Registration
                echo "----------SIGNUP------------\n";

                $email = readline("User's email: ");
                $password = readline("User's password: ");
                $roles = ['Client' => 2001];  // Default roles

                // Optionally add additional roles
                $additionalRole = readline("User additional role (Admin/Employee), or press Enter to skip: ");
                if (strcasecmp($additionalRole, 'Admin') === 0) {
                    $roles['Admin'] = 5150;
                } elseif (strcasecmp($additionalRole, 'Employee') === 0) {
                    $roles['Employee'] = 1984;
                }

                // Call the signup function
                $response = $functionnalities->signupUser($roles, $email, $password);

                if ($response && isset($response['message'])) {
                    echo "Message: " . $response['message'] . "\n";
                } else {
                    echo "Error: Unable to signup.\n";
                    if ($response) {
                        echo "Error Details: " . json_encode($response) . "\n";
                    }
                }
                break;

            case '2'://Employee Login
                echo "----------LOGIN------------\n";             
                $email = (string) readline('Enter your email (employee001@geobios.com): ');
                $password = (string) readline('Enter your password: '); 
    
                $response = $functionnalities->loginUser($email, $password);
    
                if ($response && isset($response['token'])) {
                    echo "Connection successful !\n";
                    echo "Token : " . $response['token'] . "\n";
                } else {
                    if ($response) {
                        echo "Error message : " . json_encode($response) . "\n";
                    }
                }
    
                break;
            case '3'://Create Product
                 // Replace this with your actual product details
                $productDetails = [
                    'name' => 'Very Big new Product',
                    'description' => 'This is a description of the new product.',
                    'price' => 99.99,
                    'stock' => 100
                ];
                
                $token = (string) readline('Enter the token:');
                echo "token confirm = $token\n";

                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                } else {
                    $productResponse = $functionnalities->createProduct($token, $productDetails);
                
                    if ($productResponse) {
                        print_r($productResponse);
                    } else {
                        echo "Error: Unable to create the product.\n";
                    }
                }
                break;
            case '4'://Get Product
                $token = (string) readline('Enter the token:');
                $productId = (string) readline('Enter the product Id:');
                
                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                    exit;
                }

                $productResponse = $functionnalities->getProductById($token, $productId);

                if ($productResponse && isset($productResponse['product'])) {
                    $product = $productResponse['product'];
                    echo "\tProduct Details:\n";
                    echo "\tID: {$product['_id']}\n";
                    echo "\tName: {$product['name']}\n";
                    echo "\tDescription: {$product['description']}\n";
                    echo "\tPrice: {$product['price']} €\n";
                    echo "\tStock: {$product['stock']}\n";
                } else {
                    echo "Error: Unable to fetch the product.\n";
                }
                break;
            
            case '5'://Update Product
                $productId = (string) readline('Enter procuct Id:');

                $updates = [
                    ["propName" => "name", "value" => "Updated Product Name"],
                    ["propName" => "price", "value" => 25.99]
                ];
            
                $token = (string) readline('Enter the token:');
                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                    exit;
                } else {
                     // Update the product
                    $updateResponse = $functionnalities->updateProduct($token, $productId, $updates);
                
                    if ($updateResponse && isset($updateResponse['message'])) {
                        echo "Response: " . $updateResponse['message'] . "\n";
                    } else {
                        echo "Error: Unable to update the product.\n";
                    }
                }
                break;
            case '6'://Delete Product
                $productId = (string) readline('Enter procuct Id:');
                
                $token = (string) readline('Enter the token:');
                if (!$token) {
                        echo "Error: Unable to authenticate.\n";
                } else {
                    // Delete the product
                    $deleteResponse = $functionnalities->deleteProduct($token, $productId);
    
                    if ($deleteResponse && isset($deleteResponse['message'])) {
                        echo "Response: " . $deleteResponse['message'] . "\n";
                    } else {
                        echo "Error: Unable to delete the product.\n";
                    }
                }
                   
                break;
            case '7'://Create Service
                // Replace this with your actual service details
                $serviceDetails = [
                    'name' => 'Premium  Service',
                    'description' => 'High-quality  service.',
                    'rate' => 120.00,
                    'availableEmployees' => [
                            '677d46f5056646a8ec57ad64', // Replace with actual employee IDs
                    ]
                ];
                $token = (string) readline('Enter the token:');
                if (!$token) {
                        echo "Error: Unable to authenticate.\n";
                        exit;
                } else {
                    $serviceResponse = $functionnalities->createService($token, $serviceDetails);

                    if ($serviceResponse) {
                        echo "Service created successfully:\n";
                        print_r($serviceResponse);
                    } else {
                        echo "Error: Unable to create the service.\n";
                    }
                }
                break;
            case '8'://Get Service
                $serviceId = (string) readline('Enter service Id:');

                $token = (string) readline('Enter the token:');
                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                    exit;
                } else {
                    // Fetch the service by ID
                    $service = $functionnalities->getServiceById($token, $serviceId);

                    if ($service) {
                        echo "Service Details:\n";
                        echo "ID: {$service['_id']}\n";
                        echo "Name: {$service['name']}\n";
                        echo "Rate: {$service['rate']} \n";
                        echo "Description: {$service['description']}\n";
                        echo "Created At: {$service['createdAt']}\n";
                        if (!empty($service['availableEmployees'])) {
                            echo "Available Employees:\n";
                            foreach ($service['availableEmployees'] as $employee) {
                                echo " - {$employee}\n";
                            }
                        }
                    } else {
                        echo "Error: Unable to fetch the service.\n";
                    }
                }
                break;
            case '9'://Update Service
                $serviceId = (string) readline('Enter service Id:');

                $updates = [
                        ["propName" => "name", "value" => "Updated Service Name"],
                        ["propName" => "rate", "value" => 59.99]
                ];
                
                $token = (string) readline('Enter the token:');
                if (!$token) {
                        echo "Error: Unable to authenticate.\n";
                        exit;
                } else {
                    // Update the service
                    $updateResponse = $functionnalities->updateService($token, $serviceId, $updates);
    
                    if ($updateResponse && isset($updateResponse['message'])) {
                        echo "Response: " . $updateResponse['message'] . "\n";
                    } else {
                        echo "Error: Unable to update the service.\n";
                    }
                }          
                break;
            case '10'://Delete Service
                $serviceId = (string) readline('Enter service Id:');
        
                $token = (string) readline('Enter the token:');
                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                    exit;
                } else {
                    // Delete the service
                    $deleteResponse = $functionnalities->deleteService($token, $serviceId);
                    
                    if ($deleteResponse && isset($deleteResponse['message'])) {
                        echo "Response: " . $deleteResponse['message'] . "\n";
                    } else {
                        echo "Error: Unable to delete the service.\n";
                    } 
                }
                break;
             
            case '11'://Assign Service
                $serviceId = (string) readline('Enter service Id:');
                $employeeId = (string) readline('Enter the Id of the employee:');
                $token = (string) readline('Enter the token:');

                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                    exit;
                } else {
                    $response = $functionnalities->assignEmployeeToService($token, $serviceId, $employeeId);

                    if ($response) {
                        print_r($response);
                    } else {
                        echo "Error: Unable to assign employee to the service.\n";
                    }
                }              
                break; 

            case '12'://Get Order
                $orderId = (string) readline('Enter order Id:');
                $token = (string) readline('Enter the token:');

                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                    exit;
                } else {
                    $order = $functionnalities->getOrderById($token, $orderId);

                    if ($order) {
                        print_r($order);
                    } else {
                        echo "Error: Unable to retrieve the order.\n";
                    }
                }                
                break;
            case '13'://Delete Order
                $orderId = (string) readline('Enter order Id:');
                $token = (string) readline('Enter the token:');
                if (!$token) {
                    echo "Error: Unable to authenticate.\n";
                    exit;
                } else {
                    $response = $functionnalities->deleteOrder($token, $orderId);

                    if ($response) {
                        print_r($response);
                    } else {
                        echo "Error: Unable to delete the order.\n";
                    }
                }
                break;
            
            default: 
                echo "Invalid choice.\n";
        }
    }while($choice != 0);
?>