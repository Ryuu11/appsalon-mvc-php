document.addEventListener("DOMContentLoaded", function () {
  iniciarApp();
  confirmarDelete();
});

function iniciarApp() {
  buscarPorFecha();
}

function buscarPorFecha() {
  const fechaInput = document.querySelector("#fecha");
  fechaInput.addEventListener("input", function (e) {
    const fechaSeleccionada = e.target.value;
    window.location = `?fecha=${fechaSeleccionada}`;
  });
}

function confirmarDelete() {
  // Selecciona todos los botones con la clase eliminarCita
  const $eliminarCitas = document.querySelectorAll(".eliminarCita");

  $eliminarCitas.forEach((boton) => {
    boton.addEventListener("click", (e) => {
      e.preventDefault();

      // Obtener el formulario específico asociado al botón
      const formEliminar = boton.closest(".formEliminarCita");

      Swal.fire({
        title: "Confirmación",
        text: "¿Estás seguro de que quieres eliminar este registro/cita?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3095d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        padding: "4rem",
        customClass: {
          popup: "swal-popup-large",
          title: "swal-title-large",
          htmlContainer: "swal-content-large",
          confirmButton: "swal-button-large",
          cancelButton: "swal-button-large",
        },
      }).then((result) => {
        if (result.isConfirmed) {
          // Enviar el formulario específico
          formEliminar.submit();
        }
      });
    });
  });
}
