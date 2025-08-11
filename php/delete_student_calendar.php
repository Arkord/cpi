<?php
require_once 'db.php';

$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta el parámetro id']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM students_calendars WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
