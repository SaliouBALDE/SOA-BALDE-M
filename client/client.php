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


$email = "client42@geobios.com"; 
$password = "tester"; // Remplacez par un mot de passe valide
//$token = loginUser($email, $password);

echo "========= CLIENT MENU ========\n";
echo "\t1. SIGNUP\n";
echo "\t2. LOGIN\n";
echo "\t3. GET PRODUCTS\n";
echo "\t4. GET SERVICES\n";
echo "\t5. MAKE ORDER\n";

echo "\n Make a choice:\t";
$choice = trim(fgetc(STDIN));

switch ($choice) {
    case '1':
        echo "----------SIGNUP------------\n";
        break;
    case '2': 
        echo "----------LOGIN------------\n";
        echo "\n Email (client42@geobios.com):\t";
        $email = trim(fgetc(STDIN));

        echo "\n Password (tester):\t";
        $password = trim(fgetc(STDIN));

        break;
    case '3':
        echo "----------PRODUCTS------------\n";
        break;
    case '4':
        echo "----------SERVICES------------\n";
        break;
    case '5':
        echo "----------MAKE ORDER------------\n";
        break;
    default: 
        echo "Invalid choice.\n";
}
?>