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
    // Convertir HEX a RGB
    private function hex2rgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return [$r, $g, $b];
    }

    // Encabezado
    function Header()
    {
        $ancho = $this->GetPageWidth();
        $alto = $this->GetPageHeight();

        // ==== Franja rosa (5% del alto) ====
        list($r1, $g1, $b1) = $this->hex2rgb('#ffc6bd');
        $alto_rosa = 10;
        $this->SetFillColor($r1, $g1, $b1);
        $this->Rect(0, 0, $ancho, $alto_rosa, 'F');

        // ==== Franja azul (resto del alto) ====
        list($r2, $g2, $b2) = $this->hex2rgb('#6a8bfe');
        $this->SetFillColor($r2, $g2, $b2);
        $this->Rect(0, $alto_rosa, $ancho, $alto - $alto_rosa, 'F');

        // ==== Rectángulo blanco centrado ====
        $ancho_rect = $ancho * 0.95; // 70% del ancho
        $alto_rect = 20;
        $x_rect = ($ancho - $ancho_rect) / 2;
        $y_rect = 8;
        $this->SetFillColor(255, 255, 255);
        $this->Rect($x_rect, $y_rect, $ancho_rect, $alto_rect, 'F');

        // ==== Logos ====
        $this->Image('../images/logo.png', $x_rect + 5, $y_rect + 2, 30);
        // $this->Image('../images/logo.png', $x_rect + $ancho_rect - 25, $y_rect + 5, 20);

        // ==== Título centrado ====
        $this->SetFont('Arial', 'B', 15);
        $this->SetXY(0, $y_rect + 10);
        // $this->Cell($ancho, 10, utf8_to_iso88591('Reporte de Estudiantes'), 0, 1, 'C');

        // Salto de línea para que el contenido no se monte encima
        $this->Ln(15);
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