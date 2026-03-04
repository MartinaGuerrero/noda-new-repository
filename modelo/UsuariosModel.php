<?php
class UsuariosModel
{
    private $conn;
    private $defaultHash;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->defaultHash = 'c764507e78bd93111da214cd3bc08d905c93079f4d47c44314b2088d08765e5b77d890f5f9e114ff6476de5faf39a0f838da010e1ace49242136352f060b4f45';
    }

    private function emailExists($email)
    {
        $sql = "SELECT 1 FROM usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows > 0;
    }

    public function crearUsuario($data)
    {
        $nombre = $data['nombre'] ?? '';
        $apellido = $data['apellido'] ?? '';
        $email = $data['email'] ?? '';
        $cargo = $data['cargo'] ?? '';

        if (!$nombre || !$apellido || !$email || !$cargo) {
            return ["status" => "error", "message" => "Todos los campos son obligatorios"];
        }

        if ($this->emailExists($email)) {
            return ["status" => "error", "message" => "El email ya está en uso"];
        }

        $passwordHash = $this->defaultHash;

        $sql = "INSERT INTO usuario (email, nombre, apellido, password, cargo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $email, $nombre, $apellido, $passwordHash, $cargo);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Usuario creado"];
        }

        return ["status" => "error", "message" => "Error al crear usuario"];
    }

    public function obtenerUsuariosConCargos($busqueda = null)
    {
        if ($busqueda) {
            $busqueda = $this->conn->real_escape_string($busqueda);
            $query = "SELECT * FROM usuario 
                      WHERE nombre LIKE '%$busqueda%' 
                         OR apellido LIKE '%$busqueda%' 
                         OR email LIKE '%$busqueda%' 
                         OR cargo LIKE '%$busqueda%'";
        } else {
            $query = "SELECT * FROM usuario";
        }

        $result = $this->conn->query($query);

        $response = ['usuarios' => [], 'cargos' => []];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $nombre = $row['nombre'];
                $apellido = $row['apellido'];
                $iniciales = strtoupper(substr($nombre, 0, 1) . substr($apellido, 0, 1));

                $response['usuarios'][] = [
                    'email' => $row['email'],
                    'nombre' => $row['nombre'],
                    'apellido' => $row['apellido'],
                    'cargo' => $row['cargo'],
                    'iniciales' => $iniciales
                ];

                if (!in_array($row['cargo'], $response['cargos'])) {
                    $response['cargos'][] = $row['cargo'];
                }
            }
        }

        return $response;
    }

    public function editarUsuarioAdmin($data)
    {
        $nombre = $data['nombre'] ?? '';
        $apellido = $data['apellido'] ?? '';
        $cargo = $data['cargo'] ?? '';
        $email = $data['email'] ?? '';
        $emailOriginal = $data['emailOriginal'] ?? '';

        if (!$nombre || !$apellido || !$cargo || !$email || !$emailOriginal) {
            return ['status' => 'error', 'message' => 'Todos los campos son obligatorios'];
        }

        if ($email !== $emailOriginal && $this->emailExists($email)) {
            return ['status' => 'error', 'message' => 'El email ya está en uso'];
        }

        $passwordHash = $this->defaultHash;

        $sql = "UPDATE usuario SET nombre = ?, apellido = ?, cargo = ?, email = ?, password = ?
                WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $nombre, $apellido, $cargo, $email, $passwordHash, $emailOriginal);

        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Usuario actualizado correctamente'];
        }

        return ['status' => 'error', 'message' => 'Error al actualizar el usuario'];
    }

    public function editarPerfilPropio($email, $data)
    {
        $nombre = $data['nombre'] ?? '';
        $apellido = $data['apellido'] ?? '';
        $password = $data['password'] ?? '';

        if (!$nombre || !$apellido) {
            return ['success' => false, 'message' => 'Nombre y apellido son obligatorios'];
        }

        $sql = "SELECT password, cargo FROM usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        $cargo = $usuario['cargo'];
        $passwordHash = $usuario['password'];

        if ($password && $passwordHash !== hash('sha512', $password)) {
            $passwordHash = hash('sha512', $password);
        }

        $sql = "UPDATE usuario SET nombre = ?, apellido = ?, password = ?, cargo = ? WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $apellido, $passwordHash, $cargo, $email);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Perfil actualizado correctamente'];
        }

        return ['success' => false, 'message' => 'Error al actualizar el perfil'];
    }

    public function eliminarUsuario($email)
    {
        if (!$email) {
            return ['status' => 'error', 'message' => 'Se requiere un correo electrónico para eliminar el usuario.'];
        }

        $sqlCheck = "SELECT COUNT(*) AS total FROM reserva WHERE fk_email = ?";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $res = $stmtCheck->get_result();
        $row = $res->fetch_assoc();

        if ((int)$row['total'] > 0) {
            return ['status' => 'error', 'message' => 'El usuario tiene reservas asociadas y no puede ser eliminado.'];
        }

        $sqlDelete = "DELETE FROM usuario WHERE email = ?";
        $stmtDelete = $this->conn->prepare($sqlDelete);
        $stmtDelete->bind_param("s", $email);

        if ($stmtDelete->execute()) {
            if ($stmtDelete->affected_rows > 0) {
                return ['status' => 'success', 'message' => 'Usuario eliminado correctamente.'];
            }
            return ['status' => 'error', 'message' => 'No se encontró ningún usuario con ese correo electrónico.'];
        }

        return ['status' => 'error', 'message' => 'Error al ejecutar la eliminación.'];
    }
}
