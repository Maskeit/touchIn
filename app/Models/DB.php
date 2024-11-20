<?php
require_once __DIR__ . '/../config/config.php';

class DB
{
    private $conn;

    public function connect()
    {
        // Incluye el puerto en la conexión
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn; // Retorna la conexión activa
    }
}
