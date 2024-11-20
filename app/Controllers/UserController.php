<?php
require_once './app/Models/User.php';

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
        if ($user->saveToDatabase($this->db)) {
            return ["message" => "User registered successfully.", "user_id" => $user->getId()];
        }

        return ["error" => "Failed to register user."];
    }

    public function getAllUsers()
    {
        $stmt = $this->db->prepare("SELECT id, name, email, pin, created_at FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function getUser($id){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = $id");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    public function findByPin($pin)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE pin = ?");
        $stmt->bind_param("s", $pin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }
}
