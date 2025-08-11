$(function () {
  const tabla = $("#calendarios").DataTable({
    responsive: true,
    ajax: {
      url: "php/fetch_calendars.php",
      dataSrc: "data",
    },
    language: {
      search: "Buscar:",
      info: "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
      infoEmpty: "Actualmente no hay registros para mostrar",
      loadingRecords: "Cargando...",
      lengthMenu: "_MENU_ registros por página",
      emptyTable: "No hay registros disponibles en la tabla",
      infoFiltered: "(filtrados de un total de _MAX_ registros)",
      zeroRecords: "No se encontraron registros que concuerden con el criterio",
    },
  });

  const $modal = $("#modal");
  const $form = $("#form-calendario");
  const $guardarBtn = $("#guardar-calendario");
  let modo = "crear";
  let calendarioId = null;

  // Abrir modal para agregar
  $("#btn-agregar").on("click", () => {
    modo = "crear";
    calendarioId = null;
    $("#modal-title").text("Nuevo calendario");
    $form[0].reset();
    $modal.removeClass("hidden");
  });

  // Cerrar modal
  $("#cerrar-modal, #cancelar-modal").on("click", () => {
    $modal.addClass("hidden");
    $form[0].reset();
    modo = "crear";
    calendarioId = null;
  });

  // Editar
  $("#calendarios tbody").on("click", ".btn-editar", function () {
    const $btn = $(this);

    calendarioId = $btn.data("id");
    const etiqueta = $btn.data("etiqueta");
    const fechaInicio = $btn.data("fechainicio");
    const fechaFin = $btn.data("fechafin");

    $("[name=etiqueta]").val(etiqueta);
    $("[name=fechaInicio]").val(fechaInicio);
    $("[name=fechaFin]").val(fechaFin);

    $("#modal-title").text("Editar calendario");
    modo = "editar";

    $modal.removeClass("hidden");
  });

  // Guardar (insertar o actualizar)
  $guardarBtn.on("click", async () => {
    const datos = {
      etiqueta: $("[name=etiqueta]").val(),
      fechaInicio: $("[name=fechaInicio]").val(),
      fechaFin: $("[name=fechaFin]").val(),
    };

    let url = "php/insert_calendar.php";
    if (modo === "editar" && calendarioId !== null) {
      url = "php/update_calendar.php";
      datos.id = calendarioId;
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
      calendarioId = null;

      tabla.ajax.reload(null, false);
    } catch (err) {
      $errorDiv.text("Error inesperado: " + err.message).removeClass("hidden");
    }
  });

  // Eliminar
  let calendarioIdEliminar = null;
  const $modalEliminar = $("#modal-eliminar");
  const $deleteError = $("#delete-error");

  $("#calendarios").on("click", ".btn-eliminar", function () {
    calendarioIdEliminar = $(this).data("id");
    $deleteError.addClass("hidden").text("");
    $modalEliminar.removeClass("hidden");
  });

  $("#cancelar-eliminar").on("click", () => {
    $modalEliminar.addClass("hidden");
    calendarioIdEliminar = null;
  });

  $("#confirmar-eliminar").on("click", async () => {
    if (!calendarioIdEliminar) return;

    try {
      const response = await fetch("php/delete_calendar.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: calendarioIdEliminar }),
      });

      const res = await response.json();

      if (!response.ok) {
        $deleteError
          .text(res.error || "Error desconocido")
          .removeClass("hidden");
        return;
      }

      $modalEliminar.addClass("hidden");
      calendarioIdEliminar = null;

      tabla.ajax.reload(null, false);
    } catch (err) {
      $deleteError
        .text("Error inesperado: " + err.message)
        .removeClass("hidden");
    }
  });
});
