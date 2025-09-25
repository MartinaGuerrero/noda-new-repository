<?php
// Incluir la biblioteca FPDF
require('fpdf-tutoriales-master/fpdf.php');

// Crear una clase extendida de FPDF para personalizar el PDF
class PDF extends FPDF
{
    // Función para la cabecera de la página
    function Header()
    {
        // Fondo de color azul para la cabecera
        $this->SetFillColor(85, 164, 255);
        $this->Rect(0, 0, 210, 20, 'F'); // Rectángulo de cabecera
        
        // Título
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 10, 'REPORTE', 0, 0, 'C');
        
        $this->Ln(20); // Espaciado adicional
    }

    // Función para el pie de página
    function Footer()
    {
        // Posicionar el pie de página a 1.5 cm del final de la página
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(0, 0, 0);
        
        // Mostrar el número de página
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    // Función para agregar una línea de separación
    function LineSeparator()
    {
        $this->SetDrawColor(85, 164, 255); // Color azul
        $this->SetLineWidth(1); // Grosor de la línea
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5); // Espacio después de la línea
    }

    // Función para agregar un subtítulo
    function AddSubTitle($text)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 44, 111);
        $this->Cell(0, 10, $text, 0, 1, 'L');
        $this->Ln(2); // Espacio extra después del subtítulo
    }
}

// Crear una instancia de la clase PDF
$pdf = new PDF();
$pdf->AddPage();

// Agregar subtítulo y separador
$pdf->AddSubTitle('Reporte sobre la frecuencia de uso de espacios');
$pdf->LineSeparator();

// Contenido introductorio
$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(0, 10, "Este reporte detalla la frecuencia de uso de los diferentes espacios. A continuación se presentan los datos correspondientes a las reservas realizadas el día de la fecha.");
$pdf->Ln(5); // Espacio adicional

// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'novosis_NODA');
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta SQL para obtener datos de la tabla reserva
$sql = "SELECT reserva.id_auto, reserva.hora_i, reserva.hora_f, espacio.nom_sala, usuario.email 
FROM reserva 
LEFT JOIN espacio ON reserva.fk_id_e = espacio.id_espacio 
LEFT JOIN usuario ON reserva.fk_email = usuario.email where centro = 'primaria';";

$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    // Estilo para la tabla
    $pdf->SetFillColor(230, 230, 250); // Color de fondo para las celdas
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetDrawColor(85, 164, 255); // Color del borde de la tabla
    $pdf->SetLineWidth(0.3); // Grosor de la línea

    // Encabezado de la tabla
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(20, 10, 'ID Auto', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Hora de Inicio', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Hora de Fin', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Sala', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'Funcionario', 1, 1, 'C', true);

    // Filas de la tabla
    $pdf->SetFont('Arial', '', 10);
    $fill = false; // Alterna el color de fondo
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(20, 10, $row['id_auto'], 1, 0, 'C', $fill);
        $pdf->Cell(40, 10, $row['hora_i'], 1, 0, 'C', $fill);
        $pdf->Cell(40, 10, $row['hora_f'], 1, 0, 'C', $fill);
        $pdf->Cell(40, 10, $row['nom_sala'], 1, 0, 'C', $fill);
        $pdf->Cell(50, 10, $row['email'], 1, 1, 'C', $fill);
        $fill = !$fill; // Alterna el color en cada fila
    }
} else {
    $pdf->Cell(0, 10, 'No se encontraron datos.', 0, 1, 'C');
}

// Pie del reporte
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetTextColor(128, 128, 128);
$pdf->Cell(0, 10, 'Centros educativos Impulso.', 0, 1, 'C');

// Generar el PDF y enviarlo al navegador
$pdf->Output();
?>
