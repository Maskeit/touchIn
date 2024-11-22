<?php
namespace APP\Models;

class User
{
    // Atributos de la entidad
    private $id;
    private $name;
    private $email;
    private $passwordHash; // Contraseña almacenada como hash
    private $fingerprintTemplate; // Template biométrico
    private $pin; // PIN de usuario
    private $createdAt;

    // Constructor para inicializar los datos
    public function __construct($name, $email, $fingerprintTemplate, $pin)
    {
        $this->name = $name;
        $this->email = $email;
        $this->fingerprintTemplate = $fingerprintTemplate;
        $this->pin = $pin;
        $this->createdAt = date('Y-m-d H:i:s'); // Fecha de creación actual
    }

    // Métodos Getter
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }


    public function getFingerprintTemplate()
    {
        return $this->fingerprintTemplate;
    }

    public function getPin()
    {
        return $this->pin;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    // Métodos Setter

    public function setFingerprintTemplate($template)
    {
        $this->fingerprintTemplate = $template;
    }

    // Validación de PIN
    public function validatePin($pin)
    {
        return $this->pin === $pin;
    }

    // Validación de Contraseña
    public function validatePassword($password)
    {
        return password_verify($password, $this->passwordHash);
    }


    // Método estático para obtener todos los usuarios
    public static function getAll($conn)
    {
        $stmt = $conn->prepare("SELECT id, name, email, pin, created_at FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }
    // Método estático para buscar un usuario por ID
    public static function findById($conn, $id)
    {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }
    // Método estático para buscar un usuario por PIN
    public static function findByPin($conn, $pin)
    {
        $stmt = $conn->prepare("SELECT * FROM users WHERE pin = ?");
        $stmt->bind_param("s", $pin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }
    // Guardar Usuario en la Base de Datos
    public function saveToDatabase($conn)
    {
        $stmt = $conn->prepare("INSERT INTO users (name, email, fingerprint_template, pin, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $this->name, $this->email, $this->fingerprintTemplate, $this->pin, $this->createdAt);
    
        if ($stmt->execute()) {
            $this->id = $stmt->insert_id; // Asignar el ID generado por la base de datos
            return true;
        }
    
        if ($stmt->errno === 1062) { // Código de error para clave duplicada
            throw new \Exception("PIN already exists for another user.");
        }
    
        throw new \Exception("Failed to save user: " . $stmt->error);
    }
    

    // Buscar Usuario por Correo
    public static function findByEmail($conn, $email)
    {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new User($row['name'], $row['email'], '', $row['fingerprint_template'], $row['pin']);
            $user->id = $row['id'];
            $user->createdAt = $row['created_at'];
            return $user;
        }

        return null; // Usuario no encontrado
    }
}
