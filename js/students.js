$(function () {
  $("#alumnos").DataTable({
    responsive: true,
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
});
