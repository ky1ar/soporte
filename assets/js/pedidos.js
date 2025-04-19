// Agencias en formulario
$(document).ready(function () {
  const placeholders = {
    1: ["N° de Orden", "Código de Orden"],
    2: ["N° de Tracking", ""],
    3: ["V001", "0000001"],
  };

  $("#registerTrackings .form #agency")
    .on("change", function () {
      const val = $(this).val();
      const [ph1, ph2] = placeholders[val] || ["", ""];

      $("#registerTrackings .form .track #code1").attr("placeholder", ph1);

      const code2Field =
        val === "2"
          ? `<select class="sp" id="code2" name="code2" required>
                 <option value="25">25</option>
                 <option value="24">24</option>
                 <option value="23">23</option>
                 <option value="22">22</option>
             </select>`
          : `<input type="text" id="code2" name="code2" placeholder="${ph2}" required>`;

      $("#registerTrackings .form .track #code2").replaceWith(code2Field);
    })
    .trigger("change");
});

// endpoint de documento
$(document).ready(function () {
  $("#registerTrackings .form #document").on("blur", function () {
    const doc = $(this).val().trim();

    if (doc) {
      fetch(`https://devintranet.krear3d.com/api/user/data/${doc}`, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          if (data.success && data.data) {
            if (data.data.name) {
              $("#registerTrackings .form #name").val(data.data.name);
            }
            if (data.data.phone) {
              $("#registerTrackings .form #phone").val(data.data.phone);
            }
          } else {
            console.log(
              "No se encontró el nombre o el teléfono, o hubo un error en la respuesta."
            );
          }
        })
        .catch((error) => {
          console.error("Error al consultar los datos del usuario:", error);
        });
    } else {
      console.log("No se proporcionó un valor para el documento.");
    }
  });
});

// envio en JSON
$(document).ready(function () {
  $("#registerTrackings .form .ins").on("click", function (e) {
    e.preventDefault();

    const form = $("#registerTrackings .form")[0];

    if (!form.checkValidity()) {
      console.log("Faltan datos requeridos");
      form.reportValidity();
      return;
    }

    const data = {
      order_number: $("#registerTrackings .form #order_number").val(),
      agency: $("#registerTrackings .form #agency").val(),
      code1: $("#registerTrackings .form #code1").val(),
      code2: $("#registerTrackings .form #code2").val(),
      client: {
        document: $("#registerTrackings .form #document").val(),
        name: $("#registerTrackings .form #name").val(),
        phone: $("#registerTrackings .form #phone").val(),
      },
    };

    console.log(JSON.stringify(data, null, 2));
  });
});

// Lista de Pedidos
$(document).ready(function () {
  $("#formConsulta").on("submit", function (e) {
    e.preventDefault();
    const documento = $("#documento").val().trim();
    $.ajax({
      url: "routes/getOrdersShipping.php",
      type: "POST",
      data: { documento: documento },
      dataType: "json",
      success: function (response) {
        if (response.status === "error") {
          alert(response.message);
        } else if (response.status === "success") {
          const container = $("#listOrdersShipping");
          container.html('<h1 class="title">Mis Pedidos</h1>');

          response.orders.forEach((order) => {
            let agenciaImg = "";
            if (order.id_agencia == 1) {
              agenciaImg =
                '<img class="a1" src="https://www.tiendakrear3d.com/wp-content/uploads/2025/04/logo-shalom.png" alt="Agencia 1">';
            } else if (order.id_agencia == 2) {
              agenciaImg =
                '<img class="a2" src="https://www.tiendakrear3d.com/wp-content/uploads/2025/04/logo-olva.png" alt="Agencia 2">';
            } else if (order.id_agencia == 3) {
              agenciaImg =
                '<img class="a3" src="https://www.tiendakrear3d.com/wp-content/uploads/2025/04/logo-marvisur.png" alt="Agencia 3">';
            }
            container.append(`
              <div class="order">
                <div class="head">
                  <p class="orderNum">Orden: <span>${order.orden}</span></p>
                  <p class="track">Tracking: <span>${order.code1} / ${order.code2}</span></p>
                  <div class="agencia">${agenciaImg}</div>
                </div>
                <div class="cont">
                  <div class="info">
                    <p class="fecha">29 de abril</p>
                    <p class="status">${order.nombre_status}</p>
                    <p class="name">Nombre: <span>${order.nombre_usuario}</span></p>
                    <p class="doc">Documento: <span>${order.documento}</span></p>
                  </div>
                  <div class="actions">
                    <button class="btn op" data-code1="${order.code1}" data-code2="${order.code2}" data-agency="${order.id_agencia}">Rastrear</button>
                    <a class="btn" href="https://wa.me/51908944969?text=Hola,%20quisiera%20hacer%20una%20consulta%20sobre%20mi%20compra%20con%20número%20de%20orden%3A%20${order.orden}" target="_blank">Obtener Ayuda</a>
                  </div>
                </div>
              </div>
            `);
          });

          container.fadeIn().css("display", "flex");
        }
      },
      error: function () {
        alert("Hubo un error en la solicitud");
      },
    });
  });
});

// Modal de Tracking
$(document).ready(function () {
  $("#listOrdersShipping").on("click", ".actions .btn.op", function () {
    $("#orderInfo").css("display", "flex").hide().fadeIn();
  });
  $("#orderInfo").on("click", function (e) {
    if (!$(e.target).closest(".content").length) {
      $("#orderInfo").fadeOut(function () {
        $(this).css("display", "none");
      });
    }
  });
});

function actualizarFases() {
  const $line = $("#orderInfo .content .info .line");
  $line.find(".st, .bar").css("background-color", "#ccc");
  const count = $line.find(".fas").length;
  if (count >= 1) {
    $line.find(".st.one").css("background-color", "#50d366");
  }
  if (count >= 2) {
    $line.find(".bar").eq(0).css("background-color", "#50d366");
    $line.find(".st.two").css("background-color", "#50d366");
  }
  if (count >= 3) {
    $line.find(".bar").eq(1).css("background-color", "#50d366");
    $line.find(".st.tree").css("background-color", "#50d366");
  }
}

// Utilidades generales para Scrap
function actualizarEstado($container, selector, fecha, formato = "YMDHMS") {
  const elemento = $container.find(selector);
  if (fecha) {
    const formattedDate = formatearFecha(fecha, formato);
    elemento.find(".date").text(formattedDate);
    elemento.show();
  } else {
    elemento.hide();
  }
}

function formatearFecha(fecha, formato) {
  if (!fecha) return "—";
  if (formato === "YMDHMS") {
    const [datePart, timePart] = fecha.split(/[T ]/);
    const [year, month, day] = datePart.split("-");
    return `${day}-${month}-${year} ${timePart || ""}`;
  } else if (formato === "YMD") {
    const [year, month, day] = fecha.split("-");
    return `${day}-${month}-${year}`;
  }
  return fecha;
}

function actualizarInfoBasica(
  $container,
  agencia,
  code1,
  code2,
  origen,
  destino,
  estadoActual
) {
  $container.find(".content .head .title span").text(agencia);
  $container.find(".info .cod span").text(`${code1} / ${code2}`);
  $container.find(".info .dat1 .ori span").text(origen || "—");
  $container.find(".info .dat1 .des span").text(destino || "—");
  $container
    .find(".content .head .estado-actual")
    .text(estadoActual || "SIN INFORMACIÓN");
}

// Click generalizado de Scrap para todas las agencias
$(document).on(
  "click",
  "#listOrdersShipping .order .cont .actions .btn",
  function () {
    const agencia = $(this).data("agency");
    const code1 = $(this).data("code1");
    const code2 = $(this).data("code2");
    const $orderInfo = $("#orderInfo");

    $orderInfo.find(".content .loaders").css("display", "flex");

    let url = "";
    let requestData = {};

    switch (agencia) {
      case 1:
        url = "routes/scrapShalom.php";
        requestData = { numero: code1, codigo: code2 };
        break;
      case 2:
        url = "routes/scrapOlva.php";
        requestData = { numero: code1, codigo: code2 };
        break;
      case 3:
        url = "routes/scrapMarvisur.php";
        requestData = { serie: code1, numero: code2 };
        break;
      default:
        alert("Agencia no soportada");
        return;
    }

    $.ajax({
      url,
      method: "POST",
      data: requestData,
      dataType: "json",
      success: function (response) {
        if (!response.success || !response.data) {
          alert("No se pudo obtener la información del envío.");
          return;
        }

        if (agencia === 1) {
          const estadosData = response.data.estados;
          actualizarEstado(
            $orderInfo,
            ".line .fas.entregado",
            estadosData.entregado?.fecha
          );
          actualizarEstado(
            $orderInfo,
            ".line .fas.ruta",
            estadosData.transito?.fecha
          );
          actualizarEstado(
            $orderInfo,
            ".line .fas.agencia",
            estadosData.origen?.fecha
          );

          const origen = response.data.rastreo?.origen?.nombre || "—";
          const destino = response.data.rastreo?.destino?.nombre || "—";
          actualizarInfoBasica(
            $orderInfo,
            "Shalom",
            code1,
            code2,
            origen,
            destino,
            response.data.mensaje_estado
          );
          actualizarFases(response.data.mensaje_estado);
        }

        if (agencia === 2) {
          const generalData = response.data.general;
          const detallesData = response.data.details;

          detallesData.forEach((estado) => {
            if (estado.estado_tracking === "ENTREGADO") {
              actualizarEstado(
                $orderInfo,
                ".line .fas.entregado",
                estado.fecha_creacion,
                "YMD"
              );
            } else if (estado.estado_tracking === "ASIGNADO") {
              const estadoAsignado = detallesData.reduce((max, current) =>
                new Date(max.fecha_creacion) > new Date(current.fecha_creacion)
                  ? max
                  : current
              );
              actualizarEstado(
                $orderInfo,
                ".line .fas.ruta",
                estadoAsignado.fecha_creacion,
                "YMD"
              );
            } else if (estado.estado_tracking === "RECEPCION TIENDA") {
              actualizarEstado(
                $orderInfo,
                ".line .fas.agencia",
                estado.fecha_creacion,
                "YMD"
              );
            }
          });

          actualizarInfoBasica(
            $orderInfo,
            "Olva",
            code1,
            code2,
            generalData.origen,
            generalData.destino,
            generalData.nombre_estado_tracking
          );
          actualizarFases(generalData.nombre_estado_tracking);
        }

        if (agencia === 3) {
          const detallesData = response.data.Table;
          let estadoActualComentario = null;

          detallesData.forEach((estado) => {
            if (estado.COMENTARIO === "ENTREGADO") {
              actualizarEstado(
                $orderInfo,
                ".line .fas.entregado",
                estado.FECEVENTO
              );
              estadoActualComentario = "ENTREGADO";
            } else if (estado.COMENTARIO === "EN RUTA") {
              actualizarEstado($orderInfo, ".line .fas.ruta", estado.FECEVENTO);
              if (estadoActualComentario !== "ENTREGADO")
                estadoActualComentario = "EN RUTA";
            } else if (estado.COMENTARIO === "RECEPCION") {
              actualizarEstado(
                $orderInfo,
                ".line .fas.agencia",
                estado.FECEVENTO
              );
              if (!estadoActualComentario) estadoActualComentario = "RECEPCION";
            }
          });

          const origen =
            detallesData.find((item) => item.ID === 0)?.DEPORIGEN || "—";
          const destino =
            detallesData.find((item) => item.ID === 0)?.DEPDESTINO || "—";
          actualizarInfoBasica(
            $orderInfo,
            "Marvisur",
            code1,
            code2,
            origen,
            destino,
            estadoActualComentario
          );
          actualizarFases(estadoActualComentario);
        }
      },
      error: function () {
        alert("Error al consultar la guía. Intenta nuevamente.");
      },
      complete: function () {
        $orderInfo.find(".loaders").css("display", "none");
      },
    });
  }
);

// Edt
