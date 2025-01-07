<?php
    class Functionnality {

        private b

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

        function createProduct($token, $productDetails) {
            $apiUrl = 'http://localhost:3500/products';
        
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ]);
        
            $postData = json_encode($productDetails);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        
            $response = curl_exec($ch);
        
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
        
            curl_close($ch);
        
            $decodedResponse = json_decode($response, true);
        
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
        
            return $decodedResponse;
        }

        function getProductById($token, $productId) {
            $apiUrl = "http://localhost:3500/products/$productId";
    
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
    
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
    
            curl_close($ch);
    
            $decodedResponse = json_decode($response, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
    
            return $decodedResponse;
        }

        function deleteProduct($token, $productId) {
            $apiUrl = "http://localhost:3500/products/$productId";
    
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
    
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
    
            curl_close($ch);
    
            $decodedResponse = json_decode($response, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
    
            return $decodedResponse;
        }

        function updateProduct($token, $productId, $updates) {
            $apiUrl = "http://localhost:3500/products/$productId";
    
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updates));
    
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
    
            curl_close($ch);
    
            $decodedResponse = json_decode($response, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
    
            return $decodedResponse;
        }
    
        function createService($token, $serviceDetails) {
            $apiUrl = 'http://localhost:3500/services';
    
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ]);
    
            $postData = json_encode($serviceDetails);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
    
            curl_close($ch);
    
            $decodedResponse = json_decode($response, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
    
            return $decodedResponse;
        }

        function getServiceById($token, $serviceId) {
            $apiUrl = "http://localhost:3500/services/$serviceId";
    
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
    
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
    
            curl_close($ch);
    
            $decodedResponse = json_decode($response, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
    
            return $decodedResponse;
        }

        function deleteService($token, $serviceId) {
            $apiUrl = "http://localhost:3500/services/$serviceId";
        
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
        
            $response = curl_exec($ch);
        
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
        
            curl_close($ch);
        
            $decodedResponse = json_decode($response, true);
        
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
        
            return $decodedResponse;
        }

        function updateService($token, $serviceId, $updates) {
            $apiUrl = "http://localhost:3500/services/$serviceId";
    
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updates));
    
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
    
            curl_close($ch);
    
            $decodedResponse = json_decode($response, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
    
            return $decodedResponse;
        }

        function assignEmployeeToService($token, $serviceId, $employeeId) {
            $apiUrl = "http://localhost:3500/services/$serviceId/employees";
            $data = json_encode([
                'employeeIds' => [$employeeId]
            ]);
    
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
    
            curl_close($ch);
            $decodedResponse = json_decode($response, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
    
            return $decodedResponse;
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

        function getOrderById($token, $orderId) {
            $apiUrl = "http://localhost:3500/orders/$orderId";
    
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
    
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
    
            curl_close($ch);
    
            $decodedResponse = json_decode($response, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
    
            return $decodedResponse;
        }

        function deleteOrder($token, $orderId) {
            $apiUrl = "http://localhost:3500/orders/$orderId";
    
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
    
            $response = curl_exec($ch);
    
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch) . "\n";
                curl_close($ch);
                return null;
            }
    
            curl_close($ch);
    
            $decodedResponse = json_decode($response, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON: " . json_last_error_msg() . "\n";
                return null;
            }
    
            return $decodedResponse;
        }

    }
?>