<?php
    class Functionnality {

        private $baseUrl;

        public function __construct($baseUrl) {
            $this->baseUrl =  rtrim($baseUrl, '/');
        }

        private function makeRequest($endpoint, $method, $data = [], $headers = []) {
            $url = $this->baseUrl.$endpoint;
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER,  true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $headers[] = 'content-Type: application/json';
            }

            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }

            $response =  curl_exec($ch);

            if (curl_errno($ch)) {  
                $error = curl_error($ch);   
                echo "cURL Error ".$error."\n";
                curl_close($ch);
                return null;
            }

            curl_close($ch);
            echo "Raw response: ".$response."\n";

            $decodedResponse = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error decoding JSON ".json_last_error_msg()."\n";
               //throw new Exception("Error decoding JSON: " . json_last_error_msg());
                return null;
            }

            return $decodedResponse;
        }

        function signupUser($roles, $email, $password) {
           $data = [
                'roles' => $roles,
                'email' => $email,
                'password' => $password
           ];

           return $this->makeRequest('/users/signup', 'POST', $data);
        }

        function loginUser($email, $password) {
           
            $data = [
                'email' => $email,
                'password' => $password
            ];

            return $this->makeRequest('/users/login', 'POST', $data);
        }

        function getAllProducts($token) {
            $headers = ['Authorization: Bearer ' .$token];
            $decodedResponse = $this->makeRequest('/products', 'GET', [], $headers);

            if (isset($decodedResponse['products']) && is_array($decodedResponse['products'])) {
                return $decodedResponse['products'];
            } elseif (is_array($decodedResponse)) {
                return $decodedResponse; 
            }
        }

        function createProduct($token, $productDetails) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest('/products', 'POST', $productDetails, $headers);
        }

        function getProductById($token, $productId) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/products/$productId", 'GET', [], $headers);
        }

        function deleteProduct($token, $productId) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/products/$productId", 'DELETE', [], $headers);
        }

        function updateProduct($token, $productId, $updates) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/products/$productId", 'PATCH', $updates, $headers);
        }
    
        function getAllServices($token) {
            $headers = ['Authorization: Bearer ' .$token];
            $decodedResponse = $this->makeRequest('/services', 'GET', [], $headers);
            
            if (isset($decodedResponse['services']) && is_array($decodedResponse['services'])) {
                return $decodedResponse['services'];
            } elseif (is_array($decodedResponse)) {
                return $decodedResponse; 
            }      
        }

        function createService($token, $serviceDetails) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest('/services', 'POST', $serviceDetails, $headers);
        }

        function getServiceById($token, $serviceId) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/services/$serviceId", 'GET', [], $headers);
        }

        function deleteService($token, $serviceId) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/services/$serviceId", 'DELETE', [], $headers);
        }

        function updateService($token, $serviceId, $updates) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/services/$serviceId", 'PATCH', $updates, $headers);
        }

        function assignEmployeeToService($token, $serviceId, $employeeId) {
            $data = ['employeeIds' => [$employeeId]];
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/services/$serviceId/employees", 'POST', $data, $headers);
        }
    
        function createOrder($token, $orderDetails) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest('/orders', 'POST', $orderDetails, $headers);
        }

        function getOrderById($token, $orderId) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/orders/$orderId", 'GET', [], $headers);
        }

        function deleteOrder($token, $orderId) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/orders/$orderId", 'DELETE', [], $headers);
        }

        function deleteUser($token, $userId) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/users/$userId", 'DELETE', [], $headers);
        }

        function updateUser($token, $userId, $updates) {
            $headers = ['Authorization: Bearer ' .$token];
            return $this->makeRequest("/users/$userId", 'PATCH', $updates, $headers);
        }

        function getAllProductsBis($token) {
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
        

    }
?>