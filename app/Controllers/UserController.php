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
        if (!isset($input['name'], $input['email'], $input['pin'], $input['fingerprint_template'])) {
            return ["error" => "Missing required fields."];
        }
    
        $user = new User($input['name'], $input['email'], $input['fingerprint_template'], $input['pin']);
        try {
            if ($user->saveToDatabase($this->db)) {
                return ["message" => "User registered successfully.", "user_id" => $user->getId()];
            }
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    
        return ["error" => "Failed to register user."];
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