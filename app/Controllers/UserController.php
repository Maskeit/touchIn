<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Binnacle;

class UserController
{
    private $db;

    public function __construct($conn)
    {
        $this->db = $conn; // La conexión es pasada desde index.php
    }

    public function register($input)
    {
        $response = [
            "success" => false,
            "message" => "Error to register user",
            "pin" => null,
        ];
        // Validar que se proporcionen los campos necesarios
        if (!isset($input['name'], $input['email'])) {
            return ["error" => "Missing required fields: name or email."];
        }
    
        // Verificar si el correo ya existe en la base de datos
        $existingUser = User::findByEmail($this->db, $input['email']);
        if ($existingUser) {
            $response["message"] = "User already registered";
            return $response;
        }
    
        // Generar un PIN único de 4 dígitos
        $pin = $this->generateUniquePin();
    
        // Crear el usuario con el PIN generado
        $user = new User($input['name'], $input['email'], 'default_template', $pin);
    
        try {
            if ($user->saveToDatabase($this->db)) {
                $response["success"] = true;
                $response["message"] = "User registered successfully.";
                $response["pin"] = $pin;
                return $response;
            }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    
        return $response;
    }
    
    // Método para generar un PIN único de 4 dígitos
    private function generateUniquePin()
    {
        do {
            $pin = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // Generar un PIN de 4 dígitos
            $existingPin = User::findByPin($this->db, $pin); // Verificar si ya existe
        } while ($existingPin);
    
        return $pin;
    }
    
    

    public function getAllUsers()
    {
        return User::getAll($this->db);
    }

    public function getUser($id)
    {
        return User::findById($this->db, $id);
    }

    public function findByPin($pin)
    {
        $user = User::findByPin($this->db, $pin); // Busca al usuario por PIN
        Binnacle::logAttempt($this->db, $user ? $user['id'] : null, $user !== null); // Registra el intento
    
        return $user; // Devuelve el usuario o null
    }
    
}