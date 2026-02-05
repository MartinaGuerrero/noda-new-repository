<?php
class ReservasModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // READ: Reservas generales por centro
    public function obtenerReservas($centro)
    {
        $sql = "
        SELECT 
            r.id_auto, r.fecha, r.hora_i, r.hora_f, r.observacion, r.insumo,
            u.nombre AS usuario_nombre, u.apellido AS usuario_apellido, 
            e.nom_sala, e.capacidad, e.imagen
        FROM 
            reserva r
        JOIN 
            usuario u ON r.fk_email = u.email
        JOIN 
            espacio e ON r.fk_id_e = e.id_espacio
        WHERE
            e.centro = ?
        ORDER BY 
            r.fecha ASC, r.hora_i ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $centro);
        $stmt->execute();
        $result = $stmt->get_result();

        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            $reservas[] = [
                'id' => $row["id_auto"],
                'nombre' => $row["usuario_nombre"] . " " . $row["usuario_apellido"],
                'capacidad' => $row["nom_sala"] . " (Capacidad: " . $row["capacidad"] . ")",
                'fecha' => $row["fecha"],
                'hora' => $row["hora_i"] . " - " . $row["hora_f"],
                'observacion' => $row["observacion"],
                'insumo' => $row["insumo"],
                'imagen' => $row["imagen"]
            ];
        }

        return $reservas;
    }

    // READ: Mis reservas por usuario
    public function obtenerMisReservas($email, $centro)
    {
        $sql = "
        SELECT 
            r.id_auto AS id, r.fecha, r.hora_i, r.hora_f, r.insumo AS insumos, r.observacion,
            e.nom_sala, e.imagen
        FROM reserva r
        INNER JOIN espacio e ON r.fk_id_e = e.id_espacio
        WHERE r.fk_email = ?
          AND e.centro = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $email, $centro);
        $stmt->execute();
        $result = $stmt->get_result();

        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }

        return $reservas;
    }

    // CREATE: Nueva reserva
    public function crearReserva($data, $centro)
    {
        $fecha = $data['fecha'];
        $horaI = $data['horaI'];
        $horaF = $this->calcularHoraFin($data['horaF'], $data['limpieza']);
        $insumos = $data['insumos'];
        $email = $data['email'];
        $salaId = $data['sala'];
        $observacion = $data['observaciones'];

        if (!$this->verificarDisponibilidad($salaId, $fecha, $horaI, $horaF)) {
            return ["status" => "error", "message" => "Ya existe una reserva en ese horario"];
        }

        $sql = "INSERT INTO reserva (fecha, hora_i, hora_f, observacion, insumo, fk_email, fk_id_e)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssi", $fecha, $horaI, $horaF, $observacion, $insumos, $email, $salaId);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Agenda creada exitosamente"];
        }

        return ["status" => "error", "message" => "Error al crear la agenda"];
    }

    // UPDATE: Editar reserva
    public function editarReserva($data, $centro)
    {
        $id = $data['id_reserva'];
        $fecha = $data['fecha'];
        $horaI = $data['hora_inicio'];
        $horaF = $this->calcularHoraFin($data['hora_fin'], $data['limpieza']);
        $insumos = $data['insumo'];
        $salaId = $data['sala'];
        $observacion = $data['observacion'];

        if (!$this->verificarDisponibilidad($salaId, $fecha, $horaI, $horaF, $id)) {
            return ["status" => "error", "message" => "Ya existe una reserva en ese horario"];
        }

        $sql = "UPDATE reserva
                SET fecha = ?, hora_i = ?, hora_f = ?, observacion = ?, insumo = ?, fk_id_e = ?
                WHERE id_auto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssiii", $fecha, $horaI, $horaF, $observacion, $insumos, $salaId, $id);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Reserva actualizada"];
        }

        return ["status" => "error", "message" => "Error al editar la reserva"];
    }

    // DELETE: Eliminar reserva (sin permisos aún)
    public function eliminarReserva($id)
    {
        $sql = "DELETE FROM reserva WHERE id_auto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Reserva eliminada"];
        }

        return ["status" => "error", "message" => "Error al eliminar la reserva"];
    }

    // Helper: calcular hora fin según limpieza
    public function calcularHoraFin($horaFin, $limpieza)
    {
        $minutos = ($limpieza === "true" || $limpieza === 1 || $limpieza === true) ? 15 : 5;
        return date('H:i', strtotime($horaFin . " +{$minutos} minutes"));
    }

    // Helper: disponibilidad (opcional excluir id actual en edición)
    public function verificarDisponibilidad($salaId, $fecha, $horaI, $horaF, $excluirId = null)
    {
        $sql = "SELECT 1 FROM reserva 
                WHERE fk_id_e = ? AND fecha = ?
                AND (hora_i < ? AND hora_f > ?)";
        if ($excluirId !== null) {
            $sql .= " AND id_auto != ?";
        }

        $stmt = $this->conn->prepare($sql);

        if ($excluirId !== null) {
            $stmt->bind_param("isssi", $salaId, $fecha, $horaF, $horaI, $excluirId);
        } else {
            $stmt->bind_param("isss", $salaId, $fecha, $horaF, $horaI);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows === 0;
    }
}
?>