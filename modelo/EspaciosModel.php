<?php
class EspaciosModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    
    public function crearSala($data, $centro)
{
    $nombre = $data['nombre'];
    $capacidad = $data['capacidad'];
    $recursos = $data['recursos']; 
    $rutaImagen = $data['rutaImagen']; 

    $this->conn->begin_transaction();

    try {
        $sql_sala = "INSERT INTO espacio (nom_sala, capacidad, imagen, centro) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql_sala);
        $stmt->bind_param("siss", $nombre, $capacidad, $rutaImagen, $centro);

        if (!$stmt->execute()) {
            throw new Exception("Error al insertar sala");
        }

        $last_sala_id = $this->conn->insert_id;

        $sql_recurso = "INSERT INTO recursos (recurso, id_espacio) VALUES (?, ?)";
        $stmt_recurso = $this->conn->prepare($sql_recurso);

        foreach ($recursos as $recurso) {
            $stmt_recurso->bind_param("si", $recurso, $last_sala_id);
            $stmt_recurso->execute();
        }

        $this->conn->commit();

        return [
            'status' => 'success',
            'id' => $last_sala_id,
            'nombre' => $nombre,
            'capacidad' => $capacidad,
            'recursos' => implode(', ', $recursos),
            'imagen' => $rutaImagen
        ];
    } catch (Exception $e) {
        $this->conn->rollback();
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}

    public function obtenerSalas($centro)
    {
        $sql = "
        SELECT 
            e.id_espacio AS id,
            e.nom_sala AS nombre,
            e.capacidad,
            e.imagen,
            GROUP_CONCAT(r.recurso SEPARATOR ', ') AS recursos
        FROM 
            espacio e
        LEFT JOIN
            recursos r ON e.id_espacio = r.id_espacio
        WHERE
            e.centro = ?
        GROUP BY
            e.id_espacio, e.nom_sala, e.capacidad, e.imagen
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $centro);
        $stmt->execute();
        $result = $stmt->get_result();

        $salas = [];
        while ($row = $result->fetch_assoc()) {
            $salas[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'capacidad' => $row['capacidad'],
                'imagen' => $row['imagen'],
                'recursos' => $row['recursos'] ? explode(', ', $row['recursos']) : []
            ];
        }

        return $salas;
    }


public function editarSala($data, $centro)
{
    $id = $data['id'];
    $nombre = $data['nombre'];
    $capacidad = $data['capacidad'];
    $estado = $data['estado'] ?? 'disponible';
    $imagen = $data['imagen'] ?? null;
    $recursos = $data['recursos'] ?? [];

    $this->conn->begin_transaction();

    try {
        // Actualizar sala
        $sql = "UPDATE espacio SET nom_sala = ?, capacidad = ?, estado = ?, imagen = ?
                WHERE id_espacio = ? AND centro = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sissis", $nombre, $capacidad, $estado, $imagen, $id, $centro);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar sala");
        }

        // Borrar recursos actuales
        $sql_del = "DELETE FROM recursos WHERE id_espacio = ?";
        $stmt_del = $this->conn->prepare($sql_del);
        $stmt_del->bind_param("i", $id);
        $stmt_del->execute();

        // Insertar recursos nuevos
        if (!empty($recursos)) {
            $sql_rec = "INSERT INTO recursos (recurso, id_espacio) VALUES (?, ?)";
            $stmt_rec = $this->conn->prepare($sql_rec);
            foreach ($recursos as $recurso) {
                $stmt_rec->bind_param("si", $recurso, $id);
                $stmt_rec->execute();
            }
        }

        $this->conn->commit();
        return ["status" => "success", "message" => "Sala actualizada"];
    } catch (Exception $e) {
        $this->conn->rollback();
        return ["status" => "error", "message" => $e->getMessage()];
    }
}

public function eliminarSala($id, $centro)
{
    $this->conn->begin_transaction();

    try {
        // Verificar si hay reservas asociadas
        $sql_res = "SELECT COUNT(*) as total FROM reserva WHERE fk_id_e = ?";
        $stmt_res = $this->conn->prepare($sql_res);
        $stmt_res->bind_param("i", $id);
        $stmt_res->execute();
        $res = $stmt_res->get_result();
        $row = $res->fetch_assoc();
        $stmt_res->close();

        if ($row['total'] > 0) {
            $this->conn->rollback();
            return ["status" => "error", "message" => "No se puede eliminar la sala porque tiene reservas asociadas."];
        }

        // Borrar recursos
        $sql_rec = "DELETE FROM recursos WHERE id_espacio = ?";
        $stmt_rec = $this->conn->prepare($sql_rec);
        $stmt_rec->bind_param("i", $id);
        $stmt_rec->execute();

        // Borrar sala
        $sql = "DELETE FROM espacio WHERE id_espacio = ? AND centro = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $id, $centro);

        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar sala");
        }

        $this->conn->commit();
        return ["status" => "success", "message" => "Sala eliminada"];
    } catch (Exception $e) {
        $this->conn->rollback();
        return ["status" => "error", "message" => $e->getMessage()];
    }
}



}


?>
