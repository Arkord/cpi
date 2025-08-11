<?php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT id, etiqueta, fechaInicio, fechaFin FROM calendars");
    $calendars = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $calendars[] = [
            'id' => $row['id'],
            'start' => $row['fechaInicio'],
            'end' => $row['fechaFin'],
            'display' => 'background',
            'backgroundColor' => '#3b82f6', // puedes mejorar asignando colores dinámicos si quieres
            'title' => $row['etiqueta'],
        ];
    }
    echo json_encode($calendars);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
