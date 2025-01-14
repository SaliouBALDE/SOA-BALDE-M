<?php
    require_once("Functionnality.php");

    function displayMenu() {
        echo " ======== ADMIN MENU ======= \n";
        echo "\t1. SIGNUP\n";
        echo "\t2. LOGIN\n";
        echo "\t3. DELETE USER\n";
        echo "\t4. UPDATE USER\n";
        echo "\t0. EXIT\n";
    }
    $baseUrl = 'http://localhost:3500';
    $functionnalities = new Functionnality($baseUrl);

    do {
        displayMenu();
        $choice = (int) readline('Make a choice:');

        switch ($choice) {
            case '1':echo "----------SIGNUP------------\n";
                $email = readline("User's email: ");
                $password = readline("User's password: ");

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

            case '2':echo "----------LOGIN------------\n";
                $email = (string) readline('Enter your email (abcd@test.com): ');
                $password = (string) readline('Enter your password: '); 
    
                $response = $functionnalities->loginUser($email, $password);
    
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

            case '3':echo "----------DELETE USER------------\n";
                $userId = (string) readline('Enter user Id:');
                $token = (string) readline('Enter the token:');

                if (!$token) {
                        echo "Error: Unable to authenticate.\n";
                } else {
                    // Delete the product
                    $deleteResponse = $functionnalities->deleteUser($token, $userId);
    
                    if ($deleteResponse && isset($deleteResponse['message'])) {
                        echo "Response: " . $deleteResponse['message'] . "\n";
                    } else {
                        echo "Error: Unable to delete the product.\n";
                    }
                }
    
                break;
            case '4':echo "----------UPDATE USER------------\n";
                $serviceId = (string) readline('Enter user Id:');

                $updates = [
                        ["propName" => "email", "value" => "updated@geobios.com"]
                ];
                
                $token = (string) readline('Enter the token:');
                if (!$token) {
                        echo "Error: Unable to authenticate.\n";
                        exit;
                } else {
                    // Update the service
                    $updateResponse = $functionnalities->updateUser($token, $serviceId, $updates);
    
                    if ($updateResponse && isset($updateResponse['message'])) {
                        echo "Response: " . $updateResponse['message'] . "\n";
                    } else {
                        echo "Error: Unable to update the service.\n";
                    }
                }          
                break;
            default: 
                echo "Invalid choice.\n";
        }
    }while($choice != 0);
?>