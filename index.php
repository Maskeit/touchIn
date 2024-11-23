<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\DB;
use App\Controllers\UserController;
use App\Controllers\BinnacleController;

// Lista de dominios permitidos
$allowedOrigins = [
    "http://localhost:9000",
    "https://mediumspringgreen-yak-566516.hostingersite.com"
];
// Verificar si el origen de la solicitud está en la lista de permitidos
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Credentials: true");
} else {
    http_response_code(403); // Prohibido
    echo json_encode(["error" => "Origin not allowed."]);
    exit;
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Manejo de preflight para CORS
    http_response_code(200);
    exit;
}
date_default_timezone_set('America/Mexico_City');
// Inicializa la conexión a la base de datos
$db = new DB();
$conn = $db->connect();

// Inicializa el controlador de usuarios con la conexión
$userController = new UserController($conn);

// Inicializa el controlador de la bitacora con la conexión
$binnacleController = new BinnacleController($conn);

// Ruta base y método
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
$input = json_decode(file_get_contents("php://input"), true);

// Si la solicitud es para la API
if (strpos($requestUri, '/api/') === 0) {
    // Elimina el prefijo /api/ para simplificar el manejo de rutas
    $apiPath = str_replace('/api/', '', $requestUri);
    // Manejo de rutas de API
    switch ($apiPath) {
        case 'auth/pin':
            if ($method === 'POST') {
                if (!isset($input['pin'])) {
                    http_response_code(400);
                    echo json_encode(["error" => "PIN is required."]);
                    exit;
                }

                $user = $userController->findByPin($input['pin']);
                if ($user) {
                    echo json_encode(["message" => "Authenticated", "user" => $user]);
                } else {
                    http_response_code(401);
                    echo json_encode(["error" => "Invalid PIN."]);
                }
            } else {
                http_response_code(405);
                echo json_encode(["error" => "Method not allowed."]);
            }
            break;
            /**  /api/users */
        case 'users':
            if ($method === 'GET') {
                echo json_encode($userController->getAllUsers());
            } else {
                http_response_code(405);
                echo json_encode(["error" => "Method not allowed."]);
            }
            break;
            /**  /api/user/id */
        case (strpos($apiPath, 'user/') === 0):
            // Extraer el ID del usuario desde la ruta
            $matches = [];
            if (preg_match('/^user\/(\d+)$/', $apiPath, $matches)) {
                $userId = intval($matches[1]);
                if ($method === 'GET') {
                    try {
                        $user = $userController->getUser($userId);
                        if ($user) {
                            echo json_encode($user);
                        } else {
                            http_response_code(404);
                            echo json_encode(["error" => "User not found."]);
                        }
                    } catch (Exception $e) {
                        http_response_code(500);
                        echo json_encode(["error" => "An error occurred while retrieving the user."]);
                    }
                } else {
                    http_response_code(405);
                    echo json_encode(["error" => "Method not allowed."]);
                }
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Invalid user ID format."]);
            }
            break;

            /**  /api/register */
        case 'register':
            if ($method === 'POST') {
                if (empty($input['name']) || empty($input['email'])) {
                    http_response_code(400);
                    echo json_encode(["error" => "Missing required fields: name or email."]);
                    exit;
                }
                echo json_encode($userController->register($input));
            } else {
                http_response_code(405);
                echo json_encode(["error" => "Method not allowed."]);
            }
            break;
            /**  /api/binnacle */
        case 'binnacle':
            if ($method === 'GET') {
                echo json_encode($binnacleController->getAllAttempts());
            } else {
                http_response_code(405);
                echo json_encode(["error" => "Method not allowed."]);
            }
            break;
            /**  /api/binnacle/user/id */
        case (preg_match('/^binnacle\/user\/(\d+)$/', $apiPath, $matches) ? true : false):
            var_dump($apiPath);
            if ($method === 'GET') {
                $userId = intval($matches[1]);
                echo json_encode($binnacleController->getAttemptsByUser($userId));
            } else {
                http_response_code(405);
                echo json_encode(["error" => "Method not allowed."]);
            }
            break;

        default:
            http_response_code(404);
            echo json_encode(["error" => "API endpoint not found."]);
    }
    exit;
}