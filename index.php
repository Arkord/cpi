<!DOCTYPE html>
<html class="dark">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title>Calendario</title>
  <link rel="stylesheet" href="css/custom.css" />
  <link rel="stylesheet" href="css/safari.css" />
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/index.global.min.js"></script>
  <script src="js/index.js"></script>
</head>

<body class="min-h-screen bg-white dark:bg-gray-800">
  <?php include 'partials/nav.php'; ?>

  <div id="calendar" class="w-full h-screen max-w-none"></div>

  <!-- Modal Tailwind -->
  <div
    id="modal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/20 backdrop-blur-sm">
    <div
      class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white w-full max-w-lg mx-4 rounded-xl shadow-lg overflow-hidden animate-fade-in">
      <!-- Cabecera -->
      <div
        class="px-6 py-4 border-b border-gray-300 dark:border-gray-700 relative h-14">
        <h2 class="text-lg font-semibold m-0" id="modal-title">
          Título del modal
        </h2>
        <button
          onclick="cerrarModal()"
          class="text-2xl font-bold text-gray-500 hover:text-red-500 cursor-pointer leading-none absolute right-4 top-4">
          &times;
        </button>
      </div>
      <!-- Cuerpo -->
      <div
        class="p-6 space-y-2 max-h-[60vh] overflow-y-auto"
        id="modal-content">
        <!-- Aquí se insertará contenido dinámico -->
      </div>

      <!-- Pie -->
      <div
        class="px-6 py-4 border-t border-gray-300 dark:border-gray-700 text-right">
        <button
          onclick="cerrarModal()"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition cursor-pointer">
          Cerrar
        </button>
      </div>
    </div>
  </div>
</body>

</html>