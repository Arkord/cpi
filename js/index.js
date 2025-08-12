$(function () {
  fetch("php/fetch_fullcalendar_data.php")
    .then((response) => {
      if (!response.ok) throw new Error("Error al cargar datos");
      return response.json();
    })
    .then((data) => {
      const alumnos = data.alumnos;
      const calendarios = data.calendarios;

      const calendar = new FullCalendar.Calendar($("#calendar")[0], {
        initialView: "multiMonthYear",
        contentHeight: "auto",
        locale: "es",
        buttonText: {
          today: "Hoy",
        },
        events: calendarios,
        dateClick: function (info) {
          const clickedDate = new Date(info.dateStr);

          const rangosActivos = calendarios.filter((r) => {
            const start = new Date(r.start);
            const end = new Date(r.end);
            return clickedDate >= start && clickedDate < end;
          });

          if (rangosActivos.length === 0) return;

          const primerRango = rangosActivos[0];
          const nombreCalendario =
            `📅${primerRango.title}` || `Calendario ${primerRango.id}`;

          $("#modal-title").text(nombreCalendario);
          const $contenido = $("#modal-content").empty();

          $.each(rangosActivos, function (_, rango) {
            const alumnosEnRango = alumnos.filter(
              (a) => a.calendario == rango.id
            );

            if (alumnosEnRango.length > 0) {
              const $ul = $('<ul class="list-disc list-inside space-y-1"></ul>');
              $.each(alumnosEnRango, function (_, a) {
                $ul.append(`<li>${a.nombre} (${a.matricula})</li>`);
              });
              $contenido.append($ul);
            } else {
              $contenido.append(
                '<p class="text-sm text-gray-400 italic">Sin alumnos.</p>'
              );
            }
          });

          $("#modal").removeClass("hidden");
        },
      });

      calendar.render();

      // Cerrar modal
      window.cerrarModal = function () {
        $("#modal").addClass("hidden");
        $("#modal-content").empty();
      };

      $(document).on("keydown", function (e) {
        if (e.key === "Escape") cerrarModal();
      });

      $("#modal").on("click", function (e) {
        if (e.target.id === "modal") cerrarModal();
      });
    })
    .catch((err) => {
      console.error("Error cargando datos:", err);
    });
});
