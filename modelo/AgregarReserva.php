<?php
include_once '../Controlador/conexion.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos
    $fecha = $_POST['fecha'];
    $horaI = $_POST['horaI'];
    $horaF = $_POST['horaF'];
    $insumos = $_POST['insumos'];
    $email = $_POST['email'];
    $salaId = $_POST['sala'];
    $observacion = $_POST['observaciones'];
    $servicioLimpieza = $_POST['limpieza'];
    echo $servicioLimpieza;

    if($servicioLimpieza == "true"){
        $horaF = date('H:i', strtotime($horaF . ' +15 minutes'));
    }else{
        $horaF = date('H:i', strtotime($horaF . ' +5 minutes'));
    }

    echo $fecha;
    echo $horaI;
    echo $horaF;
    echo $insumos;
    echo $email;
    echo $salaId;
    echo $servicioLimpieza;

    $query = "SELECT * FROM reserva WHERE fk_id_e = '$salaId' AND fecha = '$fecha' AND ((hora_i < '$horaF' AND hora_f > '$horaI'))";

    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        echo json_encode(["status" => "error", "message" => "Ya existe una reserva en ese horario"]);
        exit();
    }

    // Preparar la consulta
    $query = "INSERT INTO reserva (fecha, hora_i, hora_f, observacion, insumo, fk_email, fk_id_e) VALUES ('$fecha', '$horaI', '$horaF', '$observacion', '$insumos', '$email', '$salaId')";
    
    // Ejecutar la consulta
    $result = mysqli_query($conn, $query);

    // Verificar si la inserción fue exitosa
    if ($result) {
        echo json_encode(["status" => "success", "message" => "Agenda creada exitosamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al crear la agenda", "error" => mysqli_error($conn)]);
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Método de solicitud no permitido"]);
}
?>
