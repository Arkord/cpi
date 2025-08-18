<?php
require('fpdf.php');
require_once '../php/db.php'; // Conexión PDO

function utf8_to_iso88591($text)
{
    return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
}

class PDF extends FPDF
{
    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        $op = ($style == 'F') ? 'f' : (($style == 'FD' || $style == 'DF') ? 'B' : 'S');
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
        $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);

        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);

        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $x * $k, ($hp - $yc) * $k));
        $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

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

    function Header()
    {
        $ancho = $this->GetPageWidth();
        $alto = $this->GetPageHeight();

        list($r1, $g1, $b1) = $this->hex2rgb('#ffc6bd');
        $this->SetFillColor($r1, $g1, $b1);
        $this->Rect(0, 0, $ancho, 10, 'F');

        list($r2, $g2, $b2) = $this->hex2rgb('#6a8bfe');
        $this->SetFillColor($r2, $g2, $b2);
        $this->Rect(0, 10, $ancho, $alto - 10, 'F');

        $ancho_rect = $ancho * 0.95;
        $alto_rect = 20;
        $x_rect = ($ancho - $ancho_rect) / 2;
        $y_rect = 8;
        $this->SetFillColor(255, 255, 255);
        $this->RoundedRect($x_rect, $y_rect, $ancho_rect, $alto_rect, 5, 'F');

        $this->Image('../images/logo.png', $x_rect + 5, $y_rect + 2, 30);
        $this->Ln(15);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_to_iso88591('Página ') . $this->PageNo() . '/ {nb}', 0, 0, 'C');
    }
}

// Obtener parámetros
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT CONCAT(nombre, ' ', apellidos) AS nombre, matricula, aula, hora FROM students WHERE id = ?");
$stmt->execute([$id]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumno) die('Alumno no encontrado.');

// Crear PDF
$pdf = new PDF('L', 'mm', [110, 216]);
$pdf->AliasNbPages();
$pdf->AddPage();

// Nombre del alumno
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(3, 58, 143);
$pdf->SetTextColor(255, 255, 255);
$x = 10;
$y = 35;
$w = 90;
$h = 10;
$pdf->RoundedRect($x, $y, $w, $h, 2, 'F');
$pdf->SetXY($x, $y + 2);
$pdf->Cell($w, 6, utf8_to_iso88591($alumno['nombre']), 0, 0, 'C');

// Cantidad estática
$x2 = $x + $w + 10;
$pdf->RoundedRect($x2, $y, $w, $h, 2, 'F');
$pdf->SetXY($x2, $y + 2);
$pdf->Cell($w, 6, utf8_to_iso88591('$1,250.00 - Mil doscientos cincuenta pesos'), 0, 0, 'C');

// Segunda fila: Aula, Concepto, Horario, Matrícula
$y2 = $y + $h + 5;
$col_width = 45;
$col_height = 12;

$pdf->SetFillColor(3, 58, 143);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 10);

// Aula
$pdf->RoundedRect(10, $y2, $col_width, $col_height, 2, 'F');
$pdf->SetXY(10, $y2 + 3);
$pdf->Cell($col_width, 5, utf8_to_iso88591("Aula: " . $alumno['aula']), 0, 0, 'C');

// Concepto estático
$pdf->RoundedRect(10 + $col_width + 5, $y2, $col_width, $col_height, 2, 'F');
$pdf->SetXY(10 + $col_width + 5, $y2 + 2);
$pdf->MultiCell($col_width, 5, utf8_to_iso88591("Colegiatura\n15/08/2025"), 0, 'C');

// Horario
$pdf->RoundedRect(10 + ($col_width + 5) * 2, $y2, $col_width, $col_height, 2, 'F');
$pdf->SetXY(10 + ($col_width + 5) * 2, $y2 + 3);
$pdf->Cell($col_width, 5, utf8_to_iso88591("Hora: " . $alumno['hora']), 0, 0, 'C');

// Matrícula
$pdf->RoundedRect(10 + ($col_width + 5) * 3, $y2, $col_width, $col_height, 2, 'F');
$pdf->SetXY(10 + ($col_width + 5) * 3, $y2 + 3);
$pdf->Cell($col_width, 5, utf8_to_iso88591("Matrícula: " . $alumno['matricula']), 0, 0, 'C');

// Pie de recibo estático
$colorFondo = [3, 58, 143];
$colorTexto = [255, 255, 255];
$anchoPagina = $pdf->GetPageWidth() - 20;
$y = 67;
$altoFila = 23;

$ancho1 = $anchoPagina * 0.66;
$pdf->SetFillColor(...$colorFondo);
$pdf->SetTextColor(...$colorTexto);
$pdf->RoundedRect(10, $y, $ancho1, $altoFila, 3, 'F');
$pdf->SetXY(12, $y + 2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->MultiCell($ancho1 - 4, 5, utf8_to_iso88591("RECIBO DE PAGO PARA ALUMNOS\nSÓLO SERÁ VÁLIDO SI MUESTRA LAS CANTIDADES, NOMBRE DEL ASESOR, DE SUCURSAL O CONCESIONARIA.\nPOR NINGÚN MOTIVO HAY DEVOLUCIÓN DE DINERO."), 0, 'L');

$ancho2 = $anchoPagina * 0.34;
$pdf->SetFillColor(...$colorFondo);
$pdf->RoundedRect(10 + $ancho1 + 2, $y, $ancho2, $altoFila, 3, 'F');
$pdf->SetXY(10 + $ancho1 + 4, $y + 2);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell($ancho2 - 4, 5, utf8_to_iso88591("ADMINISTRACIÓN\nDIR ALMA LIDIA MEJÍA ROSALES\nASES.E"), 0, 'L');

$pdf->Output('I', 'reporte_alumno.pdf');