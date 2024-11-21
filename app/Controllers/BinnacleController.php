<?php
namespace App\Controllers;

use App\Models\Binnacle;

class BinnacleController
{
    private $db;

    public function __construct($conn)
    {
        $this->db = $conn; // Conexión a la base de datos
    }

    // Obtener todos los registros de la bitácora
    public function getAllAttempts()
    {
        return Binnacle::getAllAttempts($this->db);
    }

    // Obtener registros por usuario
    public function getAttemptsByUser($userId)
    {
        return Binnacle::getAttemptsByUser($this->db, $userId);
    }

    // Registrar un intento en la bitácora
    public function logAttempt($userId, $authorized)
    {
        $binnacle = new Binnacle($userId, $authorized);
        return $binnacle->saveToDatabase($this->db);
    }
}
