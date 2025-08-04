<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);

  $id = $data['id'] ?? null;
  $nombre = $data['nombre'] ?? '';
  $apellidos = $data['apellidos'] ?? '';
  $matricula = $data['matricula'] ?? '';

  if ($id && $nombre && $apellidos && $matricula) {
    try {
      // Obtener matrícula actual
      $stmt = $pdo->prepare("SELECT matricula FROM students WHERE id = ?");
      $stmt->execute([$id]);
      $matriculaActual = $stmt->fetchColumn();

      // Solo validar si la matrícula cambió
      if ($matricula !== $matriculaActual) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE matricula = ? AND id != ?");
        $stmt->execute([$matricula, $id]);
        if ($stmt->fetchColumn() > 0) {
          http_response_code(400);
          echo json_encode(['error' => 'La matrícula ya existe']);
          exit;
        }
      }

      // Realizar actualización
      $stmt = $pdo->prepare("UPDATE students SET nombre = ?, apellidos = ?, matricula = ? WHERE id = ?");
      $stmt->execute([$nombre, $apellidos, $matricula, $id]);

      echo json_encode(['success' => true]);
    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  } else {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos para actualización']);
  }
}