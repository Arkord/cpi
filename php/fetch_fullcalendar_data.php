<?php
require_once 'db.php';

try {
    // Obtener calendarios
    $stmtCalendarios = $pdo->query("
        SELECT id, etiqueta AS title, fechaInicio AS start, fechaFin AS end, color AS backgroundColor
        FROM calendars
    ");
    $calendarios = $stmtCalendarios->fetchAll(PDO::FETCH_ASSOC);

    // Ajustar formato para fullcalendar
    foreach ($calendarios as &$c) {
        $c['display'] = 'background';
    }

    // Obtener alumnos por calendario
    $stmtAlumnos = $pdo->query("
        SELECT sc.idCalendar AS calendario, s.nombre, s.matricula
        FROM students_calendars sc
        INNER JOIN students s ON s.id = sc.idStudent
    ");
    $alumnos = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'calendarios' => $calendarios,
        'alumnos' => $alumnos
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
