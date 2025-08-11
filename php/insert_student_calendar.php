<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$idStudent = $input['idStudent'] ?? null;
$idCalendars = $input['idCalendars'] ?? [];

if (!$idStudent || !is_array($idCalendars)) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos o inválidos']);
    exit;
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Primero borrar asignaciones no deseadas
    // Las asignaciones que NO están en $idCalendars deben borrarse
    $placeholders = implode(',', array_fill(0, count($idCalendars), '?'));

    if (count($idCalendars) > 0) {
        // Borrar asignaciones que NO están en $idCalendars para este estudiante
        $sqlDelete = "DELETE FROM students_calendars 
                  WHERE idStudent = ? AND idCalendar NOT IN ($placeholders)";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->execute(array_merge([$idStudent], $idCalendars));
    } else {
        // Si no hay calendarios asignados, borrar todas asignaciones para el estudiante
        $stmtDeleteAll = $pdo->prepare("DELETE FROM students_calendars WHERE idStudent = ?");
        $stmtDeleteAll->execute([$idStudent]);
    }

    // Ahora insertar las asignaciones nuevas que no existen aún
    // Primero obtener las asignaciones actuales restantes
    $stmtCurrent = $pdo->prepare("SELECT idCalendar FROM students_calendars WHERE idStudent = ?");
    $stmtCurrent->execute([$idStudent]);
    $current = $stmtCurrent->fetchAll(PDO::FETCH_COLUMN);

    // Filtrar los calendarios que faltan insertar
    $toInsert = array_diff($idCalendars, $current);

    $stmtInsert = $pdo->prepare("INSERT INTO students_calendars (idStudent, idCalendar) VALUES (?, ?)");
    foreach ($toInsert as $idCalendar) {
        $stmtInsert->execute([$idStudent, $idCalendar]);
    }

    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
