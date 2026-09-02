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
            r.id_auto, r.fecha, r.hora_i, r.hora_f, r.observacion, r.insumo, r.limpieza, r.fk_id_e,
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
                'sala_id' => $row["fk_id_e"] ?? null,
                'sala_nombre' => $row["nom_sala"],
                'nombre' => $row["usuario_nombre"] . " " . $row["usuario_apellido"],
                'capacidad' => $row["nom_sala"] . " (Capacidad: " . $row["capacidad"] . ")",
                'fecha' => $row["fecha"],
                'hora' => $row["hora_i"] . " - " . $row["hora_f"],
                'hora_inicio' => $row["hora_i"],
                'hora_fin' => $row["hora_f"],
                'observacion' => $row["observacion"],
                'insumo' => $row["insumo"],
                'limpieza' => (int) $row["limpieza"],
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
            r.id_auto AS id, r.fecha, r.hora_i, r.hora_f, r.insumo AS insumos, r.observacion, r.limpieza, r.fk_id_e AS sala_id,
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
        $horaF = $data['horaF'];
        $insumos = $data['insumos'];
        $email = $data['email'];
        $salaId = $data['sala'];
        $observacion = $data['observacion'] ?? ($data['observaciones'] ?? null);
        $limpieza = $this->normalizarLimpieza($data['limpieza'] ?? 0);

        if (!$this->validarRangoHorario($horaI, $horaF)) {
            return ["status" => "error", "message" => "La hora de fin debe ser posterior a la hora de inicio"];
        }

        $conflicto = $this->obtenerConflictoDisponibilidad($salaId, $fecha, $horaI, $horaF, null, $limpieza);
        if ($conflicto !== null) {
            return ["status" => "error", "message" => $this->formatearMensajeDisponibilidad($conflicto['hora_disponible'])];
        }

        $sql = "INSERT INTO reserva (fecha, hora_i, hora_f, observacion, insumo, limpieza, fk_email, fk_id_e)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssisi", $fecha, $horaI, $horaF, $observacion, $insumos, $limpieza, $email, $salaId);

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
        $horaF = $data['hora_fin'];
        $insumos = $data['insumo'];
        $salaId = $data['sala'];
        $observacion = $data['observacion'] ?? ($data['observaciones'] ?? null);
        $limpieza = $this->normalizarLimpieza($data['limpieza'] ?? 0);

        if (!$this->validarRangoHorario($horaI, $horaF)) {
            return ["status" => "error", "message" => "La hora de fin debe ser posterior a la hora de inicio"];
        }

        $conflicto = $this->obtenerConflictoDisponibilidad($salaId, $fecha, $horaI, $horaF, $id, $limpieza);
        if ($conflicto !== null) {
            return ["status" => "error", "message" => $this->formatearMensajeDisponibilidad($conflicto['hora_disponible'])];
        }

        $sql = "UPDATE reserva
                SET fecha = ?, hora_i = ?, hora_f = ?, observacion = ?, insumo = ?, limpieza = ?, fk_id_e = ?
                WHERE id_auto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssiii", $fecha, $horaI, $horaF, $observacion, $insumos, $limpieza, $salaId, $id);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Reserva actualizada"];
        }

        return ["status" => "error", "message" => "Error al editar la reserva"];
    }

    // DELETE: Eliminar reserva (sin permisos aún)
    public function eliminarReserva($id)
    {
        if (!$id) {
            return ["status" => "error", "message" => "ID de reserva invalido"];
        }

        $sql = "DELETE FROM reserva WHERE id_auto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return ["status" => "success", "message" => "Reserva eliminada"];
            }

            return ["status" => "error", "message" => "No se encontro la reserva"];
        }

        return ["status" => "error", "message" => "Error al eliminar la reserva"];
    }

    public function obtenerReservasPorFechaSala($fecha, $idSala)
    {
        if (!$fecha || !$idSala) {
            return [];
        }

        $sql = "
        SELECT 
            r.fecha,
            r.hora_i,
            r.hora_f,
            r.fk_id_e,
            u.nombre AS usuario_nombre,
            u.apellido AS usuario_apellido
        FROM 
            reserva r
        JOIN 
            usuario u ON r.fk_email = u.email
        WHERE 
            r.fecha = ? AND r.fk_id_e = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $fecha, $idSala);
        $stmt->execute();
        $result = $stmt->get_result();

        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            $reservas[] = [
                'fecha' => $row["fecha"],
                'hora' => $row["hora_i"] . " - " . $row["hora_f"],
                'nombre' => $row["usuario_nombre"] . " " . $row["usuario_apellido"],
            ];
        }

        return $reservas;
    }

    public function crearReservaDiaSemana($data)
    {
        $fecha = $data['fecha'] ?? null;
        $horaI = $data['horaI'] ?? null;
        $horaF = $data['horaF'] ?? null;
        $cantidad = isset($data['cantidad']) ? (int) $data['cantidad'] : 0;
        $email = $data['email'] ?? null;
        $salaId = isset($data['sala']) ? (int) $data['sala'] : 0;

        if (!$fecha || !$horaI || !$horaF || !$email || !$salaId) {
            return ["status" => "error", "message" => "Faltan datos obligatorios."];
        }

        if ($cantidad <= 0) {
            return ["status" => "error", "message" => "La cantidad debe ser mayor que cero."];
        }

        if (!$this->validarRangoHorario($horaI, $horaF)) {
            return ["status" => "error", "message" => "La hora de fin debe ser posterior a la hora de inicio"];
        }

        $fechasReservas = [];
        $fechaInicial = new DateTime($fecha);

        for ($i = 0; $i < $cantidad; $i++) {
            $fechaReservada = clone $fechaInicial;

            while ($fechaReservada->format('N') != 6) {
                $fechaReservada->modify('+1 day');
            }

            $fechasReservas[] = $fechaReservada->format('Y-m-d');
            $fechaInicial->modify('+1 week');
        }

        foreach ($fechasReservas as $fechaReserva) {
            $conflicto = $this->obtenerConflictoDisponibilidad($salaId, $fechaReserva, $horaI, $horaF, null, 0);
            if ($conflicto !== null) {
                return [
                    "status" => "error",
                    "message" => "Para la fecha $fechaReserva, " . strtolower($this->formatearMensajeDisponibilidad($conflicto['hora_disponible']))
                ];
            }
        }

        $sql = "INSERT INTO reserva (fecha, hora_i, hora_f, observacion, limpieza, fk_email, fk_id_e)
                VALUES (?, ?, ?, '', 0, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $this->conn->begin_transaction();

        try {
            foreach ($fechasReservas as $fechaReserva) {
                $stmt->bind_param("ssssi", $fechaReserva, $horaI, $horaF, $email, $salaId);
                if (!$stmt->execute()) {
                    throw new Exception("Error al crear la agenda");
                }
            }

            $this->conn->commit();
            return ["status" => "success", "message" => "Agenda creada exitosamente"];
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                "status" => "error",
                "message" => "Error al crear la agenda",
                "error" => $e->getMessage()
            ];
        }
    }

    // Helper: calcular hora fin según limpieza
    public function calcularHoraFin($horaFin, $limpieza)
    {
        $minutos = ($limpieza === "true" || $limpieza === 1 || $limpieza === true) ? 15 : 5;
        return date('H:i', strtotime($horaFin . " +{$minutos} minutes"));
    }

    // Helper: disponibilidad (opcional excluir id actual en edición)
    public function verificarDisponibilidad($salaId, $fecha, $horaI, $horaF, $excluirId = null, $limpiezaSolicitada = 0)
    {
        return $this->obtenerConflictoDisponibilidad($salaId, $fecha, $horaI, $horaF, $excluirId, $limpiezaSolicitada) === null;
    }

    public function obtenerConflictoDisponibilidad($salaId, $fecha, $horaI, $horaF, $excluirId = null, $limpiezaSolicitada = 0)
    {
        $horaFEfectiva = $this->calcularHoraBloqueada($horaF, $limpiezaSolicitada);
        $sql = "SELECT 
                    hora_f,
                    limpieza,
                    CASE
                        WHEN limpieza = 1 THEN ADDTIME(hora_f, '00:30:00')
                        ELSE hora_f
                    END AS hora_disponible
                FROM reserva 
                WHERE fk_id_e = ? AND fecha = ?
                AND (
                    hora_i < ?
                    AND (
                        CASE
                            WHEN limpieza = 1 THEN ADDTIME(hora_f, '00:30:00')
                            ELSE hora_f
                        END
                    ) > ?
                )";
        if ($excluirId !== null) {
            $sql .= " AND id_auto != ?";
        }
        $sql .= " ORDER BY hora_disponible ASC LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        if ($excluirId !== null) {
            $stmt->bind_param("isssi", $salaId, $fecha, $horaFEfectiva, $horaI, $excluirId);
        } else {
            $stmt->bind_param("isss", $salaId, $fecha, $horaFEfectiva, $horaI);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function validarRangoHorario($horaI, $horaF)
    {
        if (!$horaI || !$horaF) {
            return false;
        }

        return strtotime($horaF) > strtotime($horaI);
    }

    public function normalizarLimpieza($limpieza)
    {
        return ($limpieza === "true" || $limpieza === "1" || $limpieza === 1 || $limpieza === true) ? 1 : 0;
    }

    public function calcularHoraBloqueada($horaFin, $limpieza)
    {
        if ($this->normalizarLimpieza($limpieza) !== 1) {
            return $horaFin;
        }

        return date('H:i:s', strtotime($horaFin . ' +30 minutes'));
    }

    public function formatearMensajeDisponibilidad($horaDisponible)
    {
        $horaFormateada = substr((string) $horaDisponible, 0, 5);
        return "Esta sala está disponible a partir de las $horaFormateada";
    }
}
?>
