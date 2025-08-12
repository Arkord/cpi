<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $etiqueta = trim($data['etiqueta'] ?? '');
  $fechaInicio = $data['fechaInicio'] ?? '';
  $fechaFin = $data['fechaFin'] ?? '';
  $color = $data['color'] ?? '';

  if (!$etiqueta || !$fechaInicio || !$fechaFin) {
    http_response_code(400);
    echo json_encode(['error' => 'Todos los campos son obligatorios']);
    exit;
  }

  try {
    $stmt = $pdo->prepare("INSERT INTO calendars (etiqueta, fechaInicio, fechaFin, color) VALUES (?, ?, ?, ?)");
    $stmt->execute([$etiqueta, $fechaInicio, $fechaFin, $color]);

    echo json_encode(['success' => true]);
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
  }
}
