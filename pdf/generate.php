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
    // Función para dibujar rectángulos con esquinas redondeadas
    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
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
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1 * $this->k,
            ($h - $y1) * $this->k,
            $x2 * $this->k,
            ($h - $y2) * $this->k,
            $x3 * $this->k,
            ($h - $y3) * $this->k
        ));
    }

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

        // ==== Rectángulo blanco centrado con bordes redondeados ====
        $ancho_rect = $ancho * 0.95;
        $alto_rect = 20;
        $x_rect = ($ancho - $ancho_rect) / 2;
        $y_rect = 8;
        $this->SetFillColor(255, 255, 255);
        $this->RoundedRect($x_rect, $y_rect, $ancho_rect, $alto_rect, 5, 'F');

        // ==== Logos ====
        $this->Image('../images/logo.png', $x_rect + 5, $y_rect + 2, 30);
        // $this->Image('../images/logo.png', $x_rect + $ancho_rect - 25, $y_rect + 5, 20);

        // ==== Título centrado ====
        $this->SetFont('Arial', 'B', 15);
        $this->SetXY(0, $y_rect + 10);
        // $this->Cell($ancho, 10, utf8_to_iso88591('Reporte de Estudiantes'), 0, 1, 'C');

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

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetY(28);

// Texto estático
$pdf->Cell(0, 6, utf8_to_iso88591(
    "CENTRO DE INGLÉS PERSONALIZADO A.C."
), 0, 1, 'R');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetY(28);

// Colores y medidas
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(3, 58, 143); // #033a8f
$pdf->SetTextColor(255, 255, 255);

// Primera celda (nombre del alumno)
$x = 10;
$y = 35;
$w = 90;
$h = 10;
$pdf->RoundedRect($x, $y, $w, $h, 2, 'F');
$pdf->SetXY($x, $y + 2);
$pdf->Cell($w, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Juan Pérez Gómez'), 0, 0, 'C');

// Segunda celda (cantidad a pagar)
$x2 = $x + $w + 10;
$pdf->RoundedRect($x2, $y, $w, $h, 2, 'F');
$pdf->SetXY($x2, $y + 2);
$pdf->Cell($w, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '$1,250.00 - Mil doscientos cincuenta pesos'), 0, 0, 'C');

// Datos estáticos de prueba
$aula = 'Aula 5';
$concepto = "Colegiatura\n15/08/2025";
$horario = '9:00 - 11:00';
$matricula = 'MT-0254';

// Posición y medidas
$y2 = $y + $h + 5; // un poco más abajo que la primera fila
$col_width = 45;   // ancho de cada columna (4 columnas en 180 mm aprox.)
$col_height = 12;  // un poco más alto para texto multilínea

// Colores
$pdf->SetFillColor(3, 58, 143); // #033a8f
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 10);

// 1. Aula
$pdf->RoundedRect(10, $y2, $col_width, $col_height, 2, 'F');
$pdf->SetXY(10, $y2 + 3);
$pdf->Cell($col_width, 5, utf8_to_iso88591($aula), 0, 0, 'C');

// 2. Descripción / Concepto
$pdf->RoundedRect(10 + $col_width + 5, $y2, $col_width, $col_height, 2, 'F');
$pdf->SetXY(10 + $col_width + 5, $y2 + 2);
$pdf->MultiCell($col_width, 5, utf8_to_iso88591($concepto), 0, 'C');

// 3. Horario
$pdf->RoundedRect(10 + ($col_width + 5) * 2, $y2, $col_width, $col_height, 2, 'F');
$pdf->SetXY(10 + ($col_width + 5) * 2, $y2 + 3);
$pdf->Cell($col_width, 5, utf8_to_iso88591($horario), 0, 0, 'C');

// 4. Matrícula
$pdf->RoundedRect(10 + ($col_width + 5) * 3, $y2, $col_width, $col_height, 2, 'F');
$pdf->SetXY(10 + ($col_width + 5) * 3, $y2 + 3);
$pdf->Cell($col_width, 5, utf8_to_iso88591($matricula), 0, 0, 'C');


// Colores
$colorFondo = [3, 58, 143];
$colorTexto = [255, 255, 255];

// Posiciones y tamaños
$anchoPagina = $pdf->GetPageWidth() - 20; // márgenes
$y = 67;
$altoFila = 20;

// Sección 1 (66%)
$ancho1 = $anchoPagina * 0.66;
$pdf->SetFillColor($colorFondo[0], $colorFondo[1], $colorFondo[2]);
$pdf->SetTextColor($colorTexto[0], $colorTexto[1], $colorTexto[2]);
$pdf->RoundedRect(10, $y, $ancho1, $altoFila, 3, 'F');
$pdf->SetXY(12, $y + 2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->MultiCell(
    $ancho1 - 4,
    5,
    utf8_to_iso88591("RECIBO DE PAGO PARA ALUMNOS\nSÓLO SERÁ VÁLIDO SI MUESTRA LAS CANTIDADES, NOMBRE DEL ASESOR, DE SUCURSAL O CONCESIONARIA.\nPOR NINGÚN MOTIVO HAY DEVOLUCIÓN DE DINERO."),
    0,
    'L'
);

// Sección 2 (34%)
$ancho2 = $anchoPagina * 0.34;
$pdf->SetFillColor($colorFondo[0], $colorFondo[1], $colorFondo[2]);
$pdf->RoundedRect(10 + $ancho1 + 2, $y, $ancho2, $altoFila, 3, 'F');
$pdf->SetXY(10 + $ancho1 + 4, $y + 2);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(
    $ancho2 - 4,
    5,
    utf8_to_iso88591("ADMINISTRACIÓN\nDIR ALMA LIDIA MEJÍA ROSALES\nASES.E"),
    0,
    'L'
);

// Salida del PDF
$pdf->Output('I', 'reporte_estudiantes.pdf');