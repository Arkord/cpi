$(function () {
  const tabla = $("#students").DataTable({
    ajax: {
      url: "php/fetch_students.php",
      dataSrc: "data",
    },
    columns: [
      { title: "ID", data: 0 },
      { title: "Matrícula", data: 1 },
      { title: "Nombre completo", data: 2 },
      {
        title: "Acciones",
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return `
            <button class="btn-asignar px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition cursor-pointer" data-id="${row[0]}" data-nombre="${row[2]}">Asignar calendarios</button>
            <button class="btn-pdf px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition cursor-pointer ml-1" data-id="${row[0]}" data-nombre="${row[2]}" data-matricula="${row[1]}">Generar recibo</button>
          `;
        }
      },
    ],
    responsive: true,
    language: {
      search: "Buscar:",
      info: "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
      infoEmpty: "Actualmente no hay registros para mostrar",
      loadingRecords: "Cargando...",
      lengthMenu: "_MENU_ registros por página",
      emptyTable: "No hay registros disponibles en la tabla",
      infoFiltered: "(filtrados de un total de  _MAX_ registros)",
      zeroRecords: "No se encontraron registros que concuerden con el criterio",
    },
  });

  const $modal = $("#modal");
  const $formError = $("#form-error");
  const $checkboxContainer = $("#checkbox-calendarios");

  let studentId = null;

  $("#students tbody").on("click", ".btn-asignar", async function () {
    studentId = $(this).data("id");
    const nombre = $(this).data("nombre");
    $("#modal-title").text(`Asignar calendarios a ${nombre}`);

    $formError.text("").addClass("hidden");
    $checkboxContainer.empty();

    $modal.removeClass("hidden");

    try {
      // Obtener calendarios asignados al estudiante
      const resAssigned = await fetch(
        `php/fetch_student_calendars.php?idStudent=${studentId}`
      );
      const jsonAssigned = await resAssigned.json();
      if (!resAssigned.ok)
        throw new Error(jsonAssigned.error || "Error cargando asignaciones");

      // Obtener todos los calendarios disponibles
      const resAll = await fetch("php/fetch_calendars.php");
      const jsonAll = await resAll.json();
      if (!resAll.ok)
        throw new Error(jsonAll.error || "Error cargando calendarios");

      // jsonAssigned.data debe ser un array plano de IDs
      const assignedIds = new Set(jsonAssigned.data.map(String)); // convierto a string para evitar problemas

      // Construir checkboxes marcando los asignados
      jsonAll.data.forEach((c) => {
        const idCalendar = String(c[0]); // idCalendar como string
        const isChecked = assignedIds.has(idCalendar);
        const checkbox = $(`
          <label class="flex items-center space-x-2 cursor-pointer">
            <input type="checkbox" class="checkbox-calendario" value="${idCalendar}" ${isChecked ? "checked" : ""
          } />
            <span>${c[1]} (${c[2]} - ${c[3]})</span>
          </label>
        `);
        $checkboxContainer.append(checkbox);
      });
    } catch (err) {
      $formError.text(err.message).removeClass("hidden");
    }
  });

  $("#cerrar-modal, #btn-cancelar").on("click", () => {
    $modal.addClass("hidden");
    $formError.addClass("hidden").text("");
    $checkboxContainer.empty();
    studentId = null;
  });

  $("#btn-guardar").on("click", async () => {
    const selectedCalendarios = [];
    $(".checkbox-calendario:checked").each(function () {
      selectedCalendarios.push($(this).val());
    });

    // Se permite que no seleccione ninguno (asignaciones vacías)
    $formError.addClass("hidden").text("");

    try {
      const response = await fetch("php/insert_student_calendar.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          idStudent: studentId,
          idCalendars: selectedCalendarios,
        }),
      });

      const res = await response.json();

      if (!response.ok) {
        $formError.text(res.error || "Error al guardar").removeClass("hidden");
        return;
      }

      $modal.addClass("hidden");
      studentId = null;
      $checkboxContainer.empty();

      tabla.ajax.reload(null, false);
    } catch (err) {
      $formError.text("Error inesperado: " + err.message).removeClass("hidden");
    }
  });

  $("#students tbody").on("click", ".btn-pdf", function () {
    const id = $(this).data("id");
    const nombre = $(this).data("nombre");
    const matricula = $(this).data("matricula");


    window.open(`pdf/generate.php?id=${id}`, '_blank');
  });

});
