<?php
require_once __DIR__ . '/app/Models/DB.php';
require_once __DIR__ . '/app/Controllers/UserController.php';

// Configuración de encabezados
header("Content-Type: application/json");
date_default_timezone_set('America/Mexico_City');

// Inicializa la conexión a la base de datos
$db = new DB();
$conn = $db->connect();

// Inicializa el controlador de usuarios con la conexión
$userController = new UserController($conn);

// Ruta base y método
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
$input = json_decode(file_get_contents("php://input"), true);

// Manejador de rutas
if (preg_match('/^\/user\/(\d+)$/', $requestUri, $matches)) {
    // Endpoint dinámico /user/{id}
    if ($method === 'GET') {
        $id = intval($matches[1]); // Extrae el ID de la URL
        $user = $userController->getUser($id);
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "User not found."]);
        }
    } else {
        http_response_code(405); // Método no permitido
        echo json_encode(["error" => "Method not allowed."]);
    }
} else {
    // Manejo de otros endpoints
    switch ($requestUri) {
        case '/register':
            if ($method === 'POST') {
                echo json_encode($userController->register($input));
            } else {
                http_response_code(405);
                echo json_encode(["error" => "Method not allowed."]);
            }
            break;

        case '/users':
            if ($method === 'GET') {
                echo json_encode($userController->getAllUsers());
            } else {
                http_response_code(405);
                echo json_encode(["error" => "Method not allowed."]);
            }
            break;

        case '/auth/pin':
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

        default:
            http_response_code(404);
            echo json_encode(["error" => "Endpoint not found."]);
    }
}
