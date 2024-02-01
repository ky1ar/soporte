<?php
class Model {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function src_ord($postData) {
        
        $orders = $postData['inp_ord'];
        $document = $postData['inp_dcm'];
        
        $sql = "SELECT o.id FROM Orders o INNER JOIN Users c ON o.client = c.id WHERE o.number = ? AND c.document = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $orders, $document);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            return array("success" => true);
        } else {
            return array("success" => false, "message" => "Orden no encontrada, verifica los datos.");
        }
    }
    
    public function log_in($postData) {

        $email  = $postData['inp_eml'];
        $pass   = $postData['inp_pss'];
        
        $sql = "SELECT * FROM Users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        echo $pass;
        if ($stmt->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row['pass'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_levels'] = $row['levels'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_nick'] = $row['nick'];
                $_SESSION['user_role'] = $row['role'];
    
                return array("success" => true);
            } else {
                return array("success" => false, "message" => "Contraseña incorrecta");
            }
        } else {
            return array("success" => false, "message" => "Usuario no encontrado");
        }
    }
    
    public function checkOrder($orders, $document) {
        // Coloca la lógica para verificar la orden según los datos proporcionados
        // Realiza la consulta y devuelve un mensaje de éxito o fallo
    }

    public function login($email, $pass) {
        // Coloca la lógica para el inicio de sesión basado en el email y contraseña
        // Realiza la consulta y devuelve un mensaje de éxito o fallo
    }

    public function logout() {
        // Coloca la lógica para cerrar sesión
        // Realiza las operaciones necesarias para cerrar la sesión del usuario
    }

    public function searchMachine($machine) {
        // Coloca la lógica para buscar una máquina según el modelo proporcionado
        // Realiza la consulta y devuelve los resultados encontrados
    }
}
?>
