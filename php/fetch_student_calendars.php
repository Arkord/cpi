<?php
require_once 'db.php';

$idStudent = $_GET['idStudent'] ?? null;

if (!$idStudent) {
    http_response_code(400);
    echo json_encode(['error' => 'idStudent es requerido']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT idCalendar FROM students_calendars WHERE idStudent = ?");
    $stmt->execute([$idStudent]);
    $calendars = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Devuelve un array simple con los idCalendar asignados
    echo json_encode(['data' => $calendars]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
