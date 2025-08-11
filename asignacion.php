<!DOCTYPE html>
<html lang="es" class="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Asignar Calendarios a Estudiantes</title>
    <link rel="stylesheet" href="css/datatables.min.css" />
    <link rel="stylesheet" href="css/custom.css" />
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/datatables.min.js"></script>
</head>

<body class="min-h-screen bg-white dark:bg-gray-800">
    <?php include 'partials/nav.php'; ?>

    <div class="p-4">
        <table id="students" class="table-custom min-w-full divide-y bg-gray-900"></table>
    </div>

    <!-- Modal para asignar calendarios -->
    <div
        id="modal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/20 backdrop-blur-sm">
        <div
            class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white w-full max-w-lg mx-4 rounded-xl shadow-lg overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-gray-300 dark:border-gray-700 relative h-14">
                <h2 class="text-lg font-semibold m-0" id="modal-title">Asignar Calendarios</h2>
                <button
                    id="cerrar-modal"
                    class="text-2xl font-bold text-gray-500 hover:text-red-500 cursor-pointer leading-none absolute right-4 top-4">
                    &times;
                </button>
            </div>

            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <h3 class="mb-2 font-semibold">Calendarios disponibles</h3>
                <form id="form-asignar">
                    <div
                        id="checkbox-calendarios"
                        class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto border border-gray-300 rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></div>
                    <div id="form-error" class="text-red-500 mt-2 hidden"></div>
                </form>
            </div>

            <div class="px-6 py-4 border-t border-gray-300 dark:border-gray-700 text-right space-x-2">
                <button
                    id="btn-guardar"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition cursor-pointer">
                    Guardar
                </button>
                <button
                    id="btn-cancelar"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition cursor-pointer">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <script src="js/asign.js"></script>
</body>

</html>