$(function () {
  const tabla = $("#alumnos").DataTable({
    responsive: true,
    ajax: {
      url: "php/fetch_students.php",
      dataSrc: "data",
    },
    language: {
      search: "Buscar:",
      info: "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
      infoEmpty: "Actualmente no hay registros para mostrar",
      loadingRecords: "Cargando...",
      lengthMenu: "_MENU_ registros por página",
      emptyTable: "No hay registros disponibles en la tabla",
      infoFiltered: "(filtados de un total de  _MAX_ registros)",
      zeroRecords: "No se encontraron registros que concuerden con el criterio",
    },
  });

  const $modal = $("#modal");
  const $form = $("#form-alumno");
  const $guardarBtn = $("#guardar-alumno");
  let modo = "crear"; // o 'editar'
  let alumnoId = null;

  $("#btn-agregar").on("click", () => {
    modo = "crear";
    alumnoId = null;
    $("#modal-title").text("Nuevo alumno");
    $form[0].reset();
    $modal.removeClass("hidden");
  });

  $("#cerrar-modal, #cancelar-modal").on("click", () => {
    $modal.addClass("hidden");
    $form[0].reset();
    modo = "crear";
    alumnoId = null;
  });

  // Delegar clic para el botón Editar
  $("#alumnos tbody").on("click", ".btn-editar", function () {
    const $btn = $(this);

    // Obtener datos desde atributos del botón
    alumnoId = $btn.data("id");
    const nombre = $btn.data("nombre");
    const apellidos = $btn.data("apellidos");
    const matricula = $btn.data("matricula");
    const aula = $btn.data("aula");
    const hora = $btn.data("hora");

    // Prellenar el formulario
    $("[name=nombre]").val(nombre);
    $("[name=apellidos]").val(apellidos);
    $("[name=matricula]").val(matricula);
    $("[name=aula]").val(aula);
    $("[name=hora]").val(hora);

    $("#modal-title").text("Editar alumno");
    modo = "editar";

    $("#modal").removeClass("hidden");
  });

  // Guardar (insertar o actualizar)
  $guardarBtn.on("click", async () => {
    const datos = {
      nombre: $("[name=nombre]").val(),
      apellidos: $("[name=apellidos]").val(),
      matricula: $("[name=matricula]").val(),
      aula: $("[name=aula]").val(),
      hora: $("[name=hora]").val()
    };

    let url = "php/insert_student.php";
    if (modo === "editar" && alumnoId !== null) {
      url = "php/update_student.php";
      datos.id = alumnoId;
    }

    const $errorDiv = $("#form-error");

    try {
      const response = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos),
      });

      const res = await response.json();

      if (!response.ok) {
        $errorDiv.text(res.error || "Error desconocido").removeClass("hidden");
        return;
      }

      $form[0].reset();
      $modal.addClass("hidden");
      $errorDiv.addClass("hidden").text("");
      modo = "crear";
      alumnoId = null;

      tabla.ajax.reload(null, false);
    } catch (err) {
      $errorDiv.text("Error inesperado: " + err.message).removeClass("hidden");
    }
  });

  let alumnoIdEliminar = null;
  const $modalEliminar = $("#modal-eliminar");
  const $deleteError = $("#delete-error");

  $("#alumnos").on("click", ".btn-eliminar", function () {
    alumnoIdEliminar = $(this).data("id");
    $deleteError.addClass("hidden").text("");
    $modalEliminar.removeClass("hidden");
  });

  $("#cancelar-eliminar").on("click", () => {
    $modalEliminar.addClass("hidden");
    alumnoIdEliminar = null;
  });

  $("#confirmar-eliminar").on("click", async () => {
    if (!alumnoIdEliminar) return;

    try {
      const response = await fetch("php/delete_student.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: alumnoIdEliminar }),
      });

      const res = await response.json();

      if (!response.ok) {
        $deleteError
          .text(res.error || "Error desconocido")
          .removeClass("hidden");
        return;
      }

      $modalEliminar.addClass("hidden");
      alumnoIdEliminar = null;

      // Recargar la tabla usando la función de DataTables
      tabla.ajax.reload(null, false);
    } catch (err) {
      $deleteError
        .text("Error inesperado: " + err.message)
        .removeClass("hidden");
    }
  });
});
