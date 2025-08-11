<?php
require_once 'db.php';

try {
  $stmt = $pdo->query("SELECT id, etiqueta, fechaInicio, fechaFin FROM calendars");
  $data = [];

  while ($row = $stmt->fetch()) {
    $botonEditar = '<button class="btn-editar px-2 py-1 text-sm bg-yellow-500 text-white rounded hover:bg-yellow-600 transition cursor-pointer"
      data-id="' . $row['id'] . '"
      data-etiqueta="' . htmlspecialchars($row['etiqueta']) . '"
      data-fechainicio="' . $row['fechaInicio'] . '"
      data-fechafin="' . $row['fechaFin'] . '">Editar</button>';

    $botonEliminar = '<button class="btn-eliminar px-2 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition cursor-pointer"
      data-id="' . $row['id'] . '">Eliminar</button>';

    $data[] = [
      $row['id'],
      htmlspecialchars($row['etiqueta']),
      $row['fechaInicio'],
      $row['fechaFin'],
      $botonEditar . ' ' . $botonEliminar
    ];
  }

  echo json_encode(['data' => $data]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
