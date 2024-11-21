<?php

namespace App\Models;

class Binnacle
{
    private $id;
    private $userId;
    private $authorized; // Booleano para indicar si el intento fue exitoso
    private $attemptedAt; // Fecha y hora del intento de autenticación

    public function __construct($userId, $authorized, $attemptedAt = null)
    {
        $this->userId = $userId;
        $this->authorized = $authorized;
        $this->attemptedAt = $attemptedAt ?? date('Y-m-d H:i:s'); // Fecha actual por defecto
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function isAuthorized()
    {
        return $this->authorized;
    }

    public function getAttemptedAt()
    {
        return $this->attemptedAt;
    }

    // Métodos para interactuar con la base de datos
    public function saveToDatabase($conn)
    {
        $stmt = $conn->prepare("INSERT INTO binnacle (user_id, authorized, attempted_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $this->userId, $this->authorized, $this->attemptedAt);
    
        if ($stmt->execute()) {
            $this->id = $stmt->insert_id;
            return true;
        }
    
        throw new \Exception("Failed to save binnacle entry: " . $stmt->error);
    }
    

    public static function getAllAttempts($conn)
    {
        $stmt = $conn->prepare("SELECT * FROM binnacle ORDER BY attempted_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $attempts = [];

        while ($row = $result->fetch_assoc()) {
            $attempts[] = $row;
        }

        return $attempts;
    }
    // Método estático para obtener registros por usuario
    public static function getAttemptsByUser($conn, $userId)
    {
        $stmt = $conn->prepare("SELECT * FROM binnacle WHERE user_id = ? ORDER BY attempted_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $attempts = [];

        while ($row = $result->fetch_assoc()) {
            $attempts[] = $row;
        }

        return $attempts;
    }
    public static function logAttempt($conn, $userId, $authorized)
    {
        $binnacle = new self($userId, $authorized);
        $binnacle->saveToDatabase($conn);
    }
}
