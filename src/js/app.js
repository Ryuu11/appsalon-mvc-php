let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
  id: "",
  nombre: "",
  fecha: "",
  hora: "",
  servicios: [],
};

document.addEventListener("DOMContentLoaded", function () {
  iniciarApp();
});

function iniciarApp() {
  mostrarSeccion(); // muestra y oculta los servicios
  tabs(); //Cambia la seccion cuando se presiones los tabs
  botonesPaginador(); //agrega o quita los botones del paginador
  paginaSiguiente(); // self-explanatory
  paginaAnterior(); // self-explanatory
  consultarAPI(); //consulta la api en el backend de php
  idCliente();
  nombreCliente(); // anade el nombre del cliente al objeto de cita
  seleccionarFecha(); // anade la fecha de la cita en ele objeto
  seleccionarHora(); //agrega la hora de la cita en el objeto
  mostrarResumen(); // Muestra el resumen de la cita.
}

function mostrarSeccion() {
  //ocultar la seccion  que tenga la clase de mostrar

  const seccionAnterior = document.querySelector(".mostrar");
  if (seccionAnterior) {
    seccionAnterior.classList.remove("mostrar");

    //quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector(".actual");
    if (tabAnterior) {
      tabAnterior.classList.remove("actual");
    }

    //Resalta el tab actual

    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add("actual");
  }

  //seleccionar la seccion con el paso

  const pasoSelector = `#paso-${paso}`;
  const seccion = document.querySelector(pasoSelector);
  seccion.classList.add("mostrar");
}

function tabs() {
  const botones = document.querySelectorAll(".tabs button");

  botones.forEach((boton) => {
    boton.addEventListener("click", function (e) {
      paso = parseInt(e.target.dataset.paso);

      mostrarSeccion();

      botonesPaginador();
    });
  });
}

function botonesPaginador() {
  const paginaSiguiente = document.querySelector("#siguiente");

  const paginaAnterior = document.querySelector("#anterior");

  if (paso === 1) {
    paginaAnterior.classList.add("ocultar");
    paginaSiguiente.classList.remove("ocultar");
  } else if (paso === 3) {
    paginaAnterior.classList.remove("ocultar");
    paginaSiguiente.classList.add("ocultar");
    mostrarResumen();
  } else {
    paginaAnterior.classList.remove("ocultar");
    paginaSiguiente.classList.remove("ocultar");
  }

  mostrarSeccion();
}

function paginaAnterior() {
  const paginaAnterior = document.querySelector("#anterior");
  paginaAnterior.addEventListener("click", function () {
    if (paso <= pasoInicial) return;
    {
      paso--;
      botonesPaginador();
    }
  });
}

function paginaSiguiente() {
  const paginaSiguiente = document.querySelector("#siguiente");
  paginaSiguiente.addEventListener("click", function () {
    if (paso >= pasoFinal) return;
    {
      paso++;
      botonesPaginador();
    }
  });
}

async function consultarAPI() {
  try {
    const url = "/api/servicios";
    const resultado = await fetch(url);
    const servicios = await resultado.json();
    mostrarServicios(servicios);
  } catch (error) {
    console.log(error);
  }
}

function mostrarServicios(servicios) {
  servicios.forEach((servicio) => {
    const { id, nombre, precio } = servicio;

    const nombreServicio = document.createElement("P");
    nombreServicio.classList.add("nombre-servicio");
    nombreServicio.textContent = nombre;

    const precioServicio = document.createElement("P");
    precioServicio.classList.add("precio-servicio");
    precioServicio.textContent = `$${precio}`;

    const servicioDiv = document.createElement("DIV");
    servicioDiv.classList.add("servicio");
    servicioDiv.dataset.idServicio = id;
    servicioDiv.onclick = function () {
      seleccionarServicio(servicio);
    };

    servicioDiv.appendChild(nombreServicio);
    servicioDiv.appendChild(precioServicio);

    document.querySelector("#servicios").appendChild(servicioDiv);
  });
}

function seleccionarServicio(servicio) {
  const { id } = servicio;
  const { servicios } = cita;

  //identifica el elemento que se le da clic
  const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
  //comprobar si un servicio ya fue agregado.

  if (servicios.some((agregado) => agregado.id === id)) {
    cita.servicios = servicios.filter((agregado) => agregado.id !== id);
    divServicio.classList.remove("seleccionado");
  } else {
    //agrega
    cita.servicios = [...servicios, servicio];
    divServicio.classList.add("seleccionado");
  }

  console.log(cita);
}

function idCliente() {
  cita.id = document.querySelector("#id").value;
}

function nombreCliente() {
  cita.nombre = document.querySelector("#nombre").value;
}

function seleccionarFecha() {
  const inputFecha = document.querySelector("#fecha");
  inputFecha.addEventListener("input", function (e) {
    const dia = new Date(e.target.value).getUTCDay();

    if ([6, 0].includes(dia)) {
      e.target.value = "";
      mostrarAlerta("Fines de semana no permitidos", "error", ".formulario");
    } else {
      cita.fecha = e.target.value;
    }
  });
}

function seleccionarHora() {
  const inputHora = document.querySelector("#hora");

  inputHora.addEventListener("input", function (e) {
    console.log(e.target.value);

    const horaCita = e.target.value;
    const hora = horaCita.split(":")[0];
    if (hora < 10 || hora > 18) {
      e.target.value = "";
      mostrarAlerta("Hora no valida", "error", ".formulario");
    } else {
      cita.hora = e.target.value;
      console.log(cita);
    }
  });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
  //previene que se  generen mas de una alerta
  const alertaPrevia = document.querySelector(".alerta");
  if (alertaPrevia) {
    alertaPrevia.remove();
  }

  //script para crear alerta
  const alerta = document.createElement("DIV");
  alerta.textContent = mensaje;
  alerta.classList.add("alerta");
  alerta.classList.add(tipo);

  const referencia = document.querySelector(elemento);
  referencia.appendChild(alerta);

  if (desaparece) {
    //eliminar alerta
    setTimeout(() => {
      alerta.remove();
    }, 3000);
  }
}

function mostrarResumen() {
  const resumen = document.querySelector(".contenido-resumen");

  //Limpiar contenido de resumen

  while (resumen.firstChild) {
    resumen.removeChild(resumen.firstChild);
  }

  if (Object.values(cita).includes("") || cita.servicios.length === 0) {
    mostrarAlerta(
      "Falta datos de servicio, fecha, u hora",
      "error",
      ".contenido-resumen",
      false
    );

    return;
  }

  // formatear el div del resumen.

  const { nombre, fecha, hora, servicios } = cita;

  //heading para servicios en resumen
  const headingServicios = document.createElement("H3");
  headingServicios.textContent = "Resumen de servicios";
  resumen.appendChild(headingServicios);

  //iterando y mostrando los servicios
  servicios.forEach((servicio) => {
    const { id, precio, nombre } = servicio;
    const contenedorServicio = document.createElement("DIV");
    contenedorServicio.classList.add("contenedor-servicio");

    const textoServicio = document.createElement("P");
    textoServicio.textContent = nombre;

    const precioServicio = document.createElement("P");
    precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

    contenedorServicio.appendChild(textoServicio);
    contenedorServicio.appendChild(precioServicio);

    resumen.appendChild(contenedorServicio);
  });

  //heading para cita en resumen
  const headingCita = document.createElement("H3");
  headingCita.textContent = "Resumen de cita";
  resumen.appendChild(headingCita);

  const nombreCliente = document.createElement("P");
  nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

  //formatear la fecha en español

  const fechaObj = new Date(fecha);
  const mes = fechaObj.getMonth();
  const dia = fechaObj.getDate() + 2;
  const year = fechaObj.getFullYear();

  const fechaUTC = new Date(Date.UTC(year, mes, dia));
  const opciones = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  const fechaFormeteada = fechaUTC.toLocaleDateString("es-MX", opciones);

  const fechaCita = document.createElement("P");
  fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormeteada}`;

  const horaCita = document.createElement("P");
  horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

  //boton para crear una cita.

  const botonReservar = document.createElement("BUTTON");
  botonReservar.classList.add("boton");
  botonReservar.textContent = "Reservar cita";
  botonReservar.onclick = reservarcita;

  resumen.appendChild(nombreCliente);
  resumen.appendChild(fechaCita);
  resumen.appendChild(horaCita);
  resumen.appendChild(botonReservar);
}

async function reservarcita() {
  const { nombre, fecha, hora, servicios, id } = cita;
  const idServicios = servicios.map((servicio) => servicio.id);
  console.log(idServicios);

  const datos = new FormData();
  datos.append("usuarioId", id);
  datos.append("fecha", fecha);
  datos.append("hora", hora);
  datos.append("servicios", idServicios);

  //console.log([...datos]);

  try {
    const url = "/api/citas";

    const respuesta = await fetch(url, {
      method: "POST",
      body: datos,
    });

    const resultado = await respuesta.json();

    if (resultado.resultado) {
      Swal.fire({
        icon: "success",
        title: "Cita Creada",
        text: "Tu cita fue creada correctamente",
        button: "OK",
        customClass: {
          popup: "swal-popup-large",
          title: "swal-title-large",
          htmlContainer: "swal-content-large",
          confirmButton: "swal-button-large",
        },
      }).then(() => {
        setTimeout(() => {
          window.location.reload();
        }, 3000);
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Hubo un error al guardar la cita",
      customClass: {
        popup: "swal-popup-large",
        title: "swal-title-large",
        confirmButton: "swal-button-large",
      },
    });
  }
}
