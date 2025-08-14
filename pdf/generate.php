<?php
require('fpdf.php');
require_once '../php/db.php'; // Conexión PDO

// Función para reemplazar utf8_decode()
function utf8_to_iso88591($text)
{
    return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
}

// Clase PDF personalizada
class PDF extends FPDF
{
    // Encabezado
    function Header()
    {
        // Logos
        $this->Image('../images/logo.png', 10, 8, 30);
        $this->Image('../images/logo.png', 170, 8, 30);

        // Fuente para título
        $this->SetFont('Arial', 'B', 15);

        // Título centrado
        $this->Cell(0, 10, utf8_to_iso88591('Reporte de Estudiantes'), 0, 1, 'C');

        // Salto de línea
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_to_iso88591('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Texto estático
$pdf->MultiCell(0, 6, utf8_to_iso88591(
    "Este documento contiene la lista de estudiantes registrados en el sistema. Los datos son confidenciales y para uso interno únicamente."
));
$pdf->Ln(5);

// Encabezados de tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 8, 'ID', 1);
$pdf->Cell(50, 8, 'Nombre', 1);
$pdf->Cell(50, 8, 'Apellidos', 1);
$pdf->Cell(40, 8, 'Matricula', 1);
$pdf->Ln();

// Datos desde base de datos
$pdf->SetFont('Arial', '', 12);
$stmt = $pdo->query("SELECT id, nombre, apellidos, matricula FROM students");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(10, 8, $row['id'], 1);
    $pdf->Cell(50, 8, utf8_to_iso88591($row['nombre']), 1);
    $pdf->Cell(50, 8, utf8_to_iso88591($row['apellidos']), 1);
    $pdf->Cell(40, 8, $row['matricula'], 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('I', 'reporte_estudiantes.pdf');