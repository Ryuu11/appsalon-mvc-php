document.addEventListener("DOMContentLoaded", function () {
  exitoGuardarServicio();
  confirmarDelete();
});

function exitoGuardarServicio() {
  const botonesGuardar = document.querySelectorAll(".botonGuardar");

  botonesGuardar.forEach((boton) => {
    boton.addEventListener("click", async (e) => {
      e.preventDefault();

      const formGuardar = boton.closest(".formGuardar");

      if (!formGuardar) {
        console.warn("Formulario no encontrado.");
        return;
      }

      const formData = new FormData(formGuardar);

      try {
        const respuesta = await fetch(formGuardar.action, {
          method: "POST",
          body: formData,
        });

        const resultado = await respuesta.json();

        if (resultado.resultado === "error") {
          Swal.fire({
            icon: "error",
            title: "Errores de validación",
            html: resultado.alertas
              .map((alerta) => `<p>${alerta}</p>`)
              .join(""),
          });
        } else if (resultado.resultado === "exito") {
          Swal.fire({
            icon: "success",
            title: "Servicio guardado",
            text: resultado.mensaje,
            timer: 2000,
            timerProgressBar: true,
          }).then(() => {
            // Redirigir a la URL proporcionada
            window.location.href = resultado.redireccion;
          });
        }
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Error inesperado",
          text: "Revisa que los campos esten llenos correctamente.",
        });
      }
    });
  });
}

function confirmarDelete() {
  // Selecciona todos los botones con la clase eliminarServicio
  const botonesEliminar = document.querySelectorAll(".eliminarServicio");

  botonesEliminar.forEach((boton) => {
    boton.addEventListener("click", (e) => {
      e.preventDefault();

      // Obtener el formulario específico asociado al botón
      const formEliminar = boton.closest(".formEliminarServicio");

      if (!formEliminar) {
        console.warn("Formulario no encontrado para este botón.");
        return;
      }

      Swal.fire({
        title: "Confirmación",
        text: "¿Estás seguro de que quieres eliminar este registro/servicio?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3095d6",
        cancelButtonColor: "#d33",
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
          // Enviar el formulario si se confirma la acción
          formEliminar.submit();

          // Redirigir a /servicios después de un breve retraso
          Swal.fire({
            icon: "success",
            title: "Registro eliminado",
            text: "El servicio se eliminó correctamente.",
            timer: 2000,
            timerProgressBar: true,
          }).then(() => {
            window.location.href = "/servicios";
          });
        } else {
          console.log("Eliminación cancelada por el usuario.");
        }
      });
    });
  });
}
