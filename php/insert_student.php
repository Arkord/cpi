<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $nombre = trim($data['nombre'] ?? '');
  $apellidos = trim($data['apellidos'] ?? '');
  $matricula = trim($data['matricula'] ?? '');
  $aula = trim($data['aula'] ?? '');
  $hora = trim($data['hora'] ?? '');

  if ($nombre && $apellidos && $matricula) {
    try {
      // Validar si matrícula ya existe
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE matricula = ?");
      $stmt->execute([$matricula]);
      if ($stmt->fetchColumn() > 0) {
        http_response_code(409); // Conflicto
        echo json_encode(['error' => 'La matrícula ya existe']);
        exit;
      }

      // Insertar si no existe
      $stmt = $pdo->prepare("INSERT INTO students (nombre, apellidos, matricula, aula, hora) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$nombre, $apellidos, $matricula, $aula, $hora]);

      echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  } else {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
  }
}