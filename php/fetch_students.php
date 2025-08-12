<?php
require_once 'db.php';

try {
  $stmt = $pdo->query("SELECT id, nombre, apellidos, matricula, IFNULL(aula, '') AS aula, IFNULL(hora, '') AS hora FROM students");
  $data = [];

  while ($row = $stmt->fetch()) {
    $nombreCompleto = $row['nombre'] . ' ' . $row['apellidos'];
    $botonEditar = '<button class="btn-editar px-2 py-1 text-sm bg-yellow-500 text-white rounded hover:bg-yellow-600 transition cursor-pointer"
      data-id="' . $row['id'] . '"
      data-nombre="' . htmlspecialchars($row['nombre']) . '"
      data-apellidos="' . htmlspecialchars($row['apellidos']) . '"
      data-matricula="' . htmlspecialchars($row['matricula']) . '"
      data-aula="' . htmlspecialchars($row['aula']) . '"
      data-hora="' . htmlspecialchars($row['hora']) . '">Editar</button>';

    $botonEliminar = '<button class="btn-eliminar px-2 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition cursor-pointer"
      data-id="' . $row['id'] . '">Eliminar</button>';

    $data[] = [
      $row['id'],
      $row['matricula'],
      $nombreCompleto,
      $row['aula'],
      $row['hora'],
      $botonEditar . ' ' . $botonEliminar
    ];
  }

  echo json_encode(['data' => $data]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}