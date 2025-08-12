<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['id'] ?? null;
  $etiqueta = trim($data['etiqueta'] ?? '');
  $fechaInicio = $data['fechaInicio'] ?? '';
  $fechaFin = $data['fechaFin'] ?? '';
  $color = $data['color'] ?? '';

  if (!$id || !$etiqueta || !$fechaInicio || !$fechaFin) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
  }

  try {
    $stmt = $pdo->prepare("UPDATE calendars SET etiqueta=?, fechaInicio=?, fechaFin=?, color=? WHERE id=?");
    $stmt->execute([$etiqueta, $fechaInicio, $fechaFin, $color, $id]);

    if ($stmt->rowCount() > 0) {
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['error' => 'No se actualizó el calendario']);
    }
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
  }
}
