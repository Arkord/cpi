<!DOCTYPE html>
<html class="dark">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title>Alumnos</title>
  <link rel="stylesheet" href="css/datatables.min.css" />
  <link rel="stylesheet" href="css/custom.css" />
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/datatables.min.js"></script>
</head>

<body class="min-h-screen bg-white dark:bg-gray-800">
  <?php include 'partials/nav.php'; ?>
  <table id="alumnos" class="table-custom min-w-full divide-y bg-gray-900">
    <thead class="bg-gray-50">
      <tr>
        <th>ID</th>
        <th>Matrícula</th>
        <th>Nombre completo</th>
        <th>Calendarios</th>
        <th>Acciones</th>
      </tr>
    </thead>
  </table>
  <!-- Botón para abrir el modal -->
  <div class="p-4">
    <button id="btn-agregar" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition cursor-pointer">
      Agregar alumno
    </button>
  </div>

  <!-- Modal (estilo unificado) -->
  <div
    id="modal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/20 backdrop-blur-sm">
    <div
      class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white w-full max-w-lg mx-4 rounded-xl shadow-lg overflow-hidden animate-fade-in">

      <!-- Cabecera -->
      <div class="px-6 py-4 border-b border-gray-300 dark:border-gray-700 relative h-14">
        <h2 class="text-lg font-semibold m-0" id="modal-title">Nuevo alumno</h2>
        <button
          id="cerrar-modal"
          class="text-2xl font-bold text-gray-500 hover:text-red-500 cursor-pointer leading-none absolute right-4 top-4">
          &times;
        </button>
      </div>

      <!-- Contenido -->
      <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto" id="modal-content">
        <form id="form-alumno" class="space-y-4">
          <div>
            <label class="block text-sm mb-1">Nombre</label>
            <input
              type="text"
              name="nombre"
              required
              class="w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
          </div>
          <div>
            <label class="block text-sm mb-1">Apellidos</label>
            <input
              type="text"
              name="apellidos"
              required
              class="w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
          </div>
          <div>
            <label class="block text-sm mb-1">Matrícula</label>
            <input
              type="text"
              name="matricula"
              required
              class="w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white" />

          </div>
          <div id="form-error" class="text-red-500 text-sm hidden"></div>
        </form>
      </div>

      <!-- Pie del modal -->
      <div class="px-6 py-4 border-t border-gray-300 dark:border-gray-700 text-right">
        <button
          type="button"
          id="guardar-alumno"
          class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition cursor-pointer">
          Guardar
        </button>
        <button
          type="button"
          id="cancelar-modal"
          class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition cursor-pointer">
          Cancelar
        </button>
      </div>
    </div>
  </div>

  <script src="js/students.js"></script>
</body>

</html>