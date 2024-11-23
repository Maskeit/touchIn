<?php

namespace App\Controllers;


class Headers
{
    public function validateHeaders($skipForRoutes = [])
    {
        global $requestUri;
        foreach ($skipForRoutes as $route) {
            if (strpos($requestUri, $route) === 0) {
                return; // Saltar validación para esta ruta
            }
        }

        // Validaciones como antes
        if (!isset($_SERVER['HTTP_ACCEPT']) || $_SERVER['HTTP_ACCEPT'] !== 'application/json') {
            http_response_code(400);
            echo json_encode(["error" => "Missing or invalid 'Accept' header. Expected 'application/json'."]);
            exit;
        }

        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'PATCH'])) {
            if (!isset($_SERVER['CONTENT_TYPE']) || $_SERVER['CONTENT_TYPE'] !== 'application/json') {
                http_response_code(400);
                echo json_encode(["error" => "Missing or invalid 'Content-Type' header. Expected 'application/json'."]);
                exit;
            }
        }
    }
}
