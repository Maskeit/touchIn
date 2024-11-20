<?php
//require_once './Models/DB.php';
require_once __DIR__ . '/app/Models/DB.php';
require_once __DIR__ . '/app/Controllers/UserController.php';

// Configuración de encabezados
header("Content-Type: application/json");
date_default_timezone_set('America/Mexico_City');

// Inicializa la conexión a la base de datos
$db = new DB();           // Instancia de DB (sin parámetros)
$conn = $db->connect();   // Obtén la conexión activa

// Inicializa el controlador de usuarios con la conexión
$userController = new UserController($conn);

// Ruta base y método
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
$input = json_decode(file_get_contents("php://input"), true);

switch ($requestUri) {
    case '/register': // endpoint del registro de usuarios
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
