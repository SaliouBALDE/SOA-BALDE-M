<?php
    use PHPUnit\Framework\TestCase;

    class UserSignupTest extends TestCase
    {
        private $apiUrl;

        protected function setUp(): void {
            // URL de l'API pour la création d'utilisateur
            $this->apiUrl = 'http://localhost:5000/api/users/signup';
        }

        public function testUserSignupWithRoleSuccess() {
            // Données pour le test avec rôle
            $userData = [
                'email' => 'testuserwithrole@example.com',
                'password' => 'securepassword',
                'roles' => [
                    'Client' => 2001
                ]
            ];

            // Requête POST vers l'API
            $response = $this->makePostRequest($this->apiUrl, $userData);

            // Vérifier que la réponse est correcte
            $this->assertEquals(200, $response['status_code']);
            $this->assertArrayHasKey('message', $response['data']);
            $this->assertEquals('User succefully created!', $response['data']['message']);
        }

        public function testUserSignupDuplicateEmailWithRole() {
            // Données pour le test (email existant avec rôle)
            $userData = [
                'email' => 'existinguserwithrole@example.com',
                'password' => 'securepassword',
                'roles' => [
                    'Employee' => 2001
                ]
            ];

            // Requête POST vers l'API
            $response = $this->makePostRequest($this->apiUrl, $userData);

            // Vérifier que l'email dupliqué retourne une erreur
            $this->assertEquals(409, $response['status_code']);
            $this->assertArrayHasKey('message', $response['data']);
            $this->assertEquals('This mail already exists', $response['data']['message']);
        }

        public function testUserSignupMissingRole() {
            // Données pour le test (rôle manquant)
            $userData = [
                'email' => 'testusernorole@example.com',
                'password' => 'securepassword'
            ];

            // Requête POST vers l'API
            $response = $this->makePostRequest($this->apiUrl, $userData);

            // Vérifier que l'API accepte un rôle par défaut ou retourne une erreur
            $this->assertEquals(200, $response['status_code']);
            $this->assertArrayHasKey('message', $response['data']);
            $this->assertEquals('User succefully created!', $response['data']['message']);
        }

        private function makePostRequest($url, $data) {
            // Initialisation de cURL
            $ch = curl_init($url);

            // Configuration de la requête
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            // Exécuter la requête
            $response = curl_exec($ch);

            // Gestion des erreurs
            if (curl_errno($ch)) {
                $errorMessage = curl_error($ch);
                curl_close($ch);
                return [
                    'status_code' => 500,
                    'data' => ['error' => $errorMessage]
                ];
            }

            // Récupérer le code HTTP
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Fermer la session cURL
            curl_close($ch);

            return [
                'status_code' => $statusCode,
                'data' => json_decode($response, true)
            ];
        }
    }
?>