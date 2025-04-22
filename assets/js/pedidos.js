// Agencias en formulario
$(document).ready(function () {
  const config = {
    1: {
      p1: "N° de Orden",
      m1: 8,
      type2: "text",
      p2: "Código de Orden",
      m2: 4,
    },
    2: {
      p1: "N° de Tracking",
      m1: 7,
      type2: "select",
      options: ["25", "24", "23", "22"],
    },
    3: { p1: "V001", m1: 4, type2: "text", p2: "0000001", m2: 7 },
  };

  $("#registerTrackings .form #agency")
    .on("change", function () {
      const { p1, m1, type2, p2, m2, options } = config[$(this).val()];

      const $code1 = $("#registerTrackings .form .track #code1");
      $code1.attr({ placeholder: p1, maxlength: m1 }).val(""); // limpia code1

      const code2Field =
        type2 === "select"
          ? `<select class="sp" id="code2" name="code2" required>
          ${options.map((o) => `<option value="${o}">${o}</option>`).join("")}
        </select>`
          : `<input type="text" id="code2" name="code2" placeholder="${
              p2 || ""
            }" maxlength="${m2 || 15}" required>`;

      $("#registerTrackings .form .track #code2").replaceWith(code2Field);
    })
    .trigger("change");
});

// endpoint de documento
$(document).ready(function () {
  $("#registerTrackings .form #order_number").on("blur", function () {
    const orderNumber = $(this).val().trim();

    if (orderNumber) {
      fetch(`https://devintranet.krear3d.com/api/order/id/${orderNumber}`)
        .then((response) => {
          if (!response.ok) return Promise.reject();
          return response.json();
        })
        .then((data) => {
          if (data.success && data.data.client) {
            const { document, name, phone, id: clientId } = data.data.client;
            const userOrderId = data.data.user_order_id;

            // Rellenar campos visibles
            $("#registerTrackings .form #document")
              .val(document)
              .prop("disabled", true);
            $("#registerTrackings .form #name")
              .val(name)
              .prop("disabled", true);
            $("#registerTrackings .form #phone")
              .val(phone)
              .prop("disabled", true);

            // Establecer los data-* como atributos HTML visibles
            $("#registerTrackings .form #order_number")
              .attr("data-client-id", clientId)
              .attr("data-user-order-id", userOrderId);
          }
        })
        .catch(() => {
          console.warn("No se pudo obtener datos del pedido.");
        });
    }
  });
});

// registro en JSON
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
      agency_id: $("#registerTrackings .form #agency").val(),
      admin_id: 3,
      code1: $("#registerTrackings .form #code1").val(),
      code2: $("#registerTrackings .form #code2").val(),
      user_order_id: $("#registerTrackings .form #user_order_id").val(),
      client_id: $("#registerTrackings .form #client_id").val(),
      client: {
        document: $("#registerTrackings .form #document").val(),
        name: $("#registerTrackings .form #name").val(),
        phone: $("#registerTrackings .form #phone").val().replace(/^51/, ""), // 👈 Aquí se formatea solo al enviar
      },
    };

    console.log("Datos enviados:", JSON.stringify(data, null, 2));

    fetch("https://devintranet.krear3d.com/api/tracking/add", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log("Tracking registrado con éxito:", data);
          $("#registerTrackings .form")[0].reset();
        } else {
          console.error("Error al registrar el tracking:", data);
        }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
      });
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

// Actualizar Fases
function actualizarFases() {
  const $line = $("#orderInfo .content .info .line");
  const $fasesVisibles = $line.find(".fas:not([style*='display: none'])");
  const count = $fasesVisibles.length;
  $("#orderInfo .content .status .fases .st").removeClass("activo");
  $("#orderInfo .content .status .fases .bar").removeClass("activo");
  if (count >= 1) {
    $("#orderInfo .content .status .fases .st.one").addClass("activo");
  }
  if (count >= 2) {
    $("#orderInfo .content .status .fases .bar.one").addClass("activo");
    $("#orderInfo .content .status .fases .st.two").addClass("activo");
  }
  if (count >= 3) {
    $("#orderInfo .content .status .fases .bar.two").addClass("activo");
    $("#orderInfo .content .status .fases .st.tree").addClass("activo");
  }
}

// Endpoint Shalom
$(document).on(
  "click",
  '#listOrdersShipping .order .cont .actions .btn[data-agency="1"]',
  function () {
    const code1 = $(this).data("code1");
    const code2 = $(this).data("code2");
    const $orderInfo = $("#orderInfo");
    $orderInfo.find(".content .loaders").css("display", "flex");

    $.ajax({
      url: "routes/scrapShalom.php",
      method: "POST",
      data: { numero: code1, codigo: code2 },
      dataType: "json",
      success: function (response) {
        if (response.success && response.data) {
          const estadosData = response.data.estados;

          function actualizarEstado(selector, estado) {
            const fecha = estado?.fecha;
            const elemento = $orderInfo.find(selector);

            if (fecha) {
              const [datePart, timePart] = fecha.split(" ");
              const [year, month, day] = datePart.split("-");
              const formattedDate = `${day}-${month}-${year} ${timePart}`;
              elemento.find(".date").text(formattedDate);
              elemento.show();
            } else {
              elemento.hide();
            }
          }
          actualizarEstado(".line .fas.entregado", estadosData.entregado);
          actualizarEstado(".line .fas.ruta", estadosData.transito);
          actualizarEstado(".line .fas.agencia", estadosData.origen);

          const rastreoData = response.data.rastreo;
          const mensajeEstado = response.data.mensaje_estado;
          const origen = rastreoData?.origen
            ? `${rastreoData.origen.nombre || "—"}, ${
                rastreoData.origen.distrito || "—"
              }, ${rastreoData.origen.provincia || "—"}, ${
                rastreoData.origen.departamento || "—"
              }`
            : "—";
          const destino = rastreoData?.destino
            ? `${rastreoData.destino.nombre || "—"}, ${
                rastreoData.destino.distrito || "—"
              }, ${rastreoData.destino.provincia || "—"}, ${
                rastreoData.destino.departamento || "—"
              }`
            : "—";

          $orderInfo.find(".content .head .title span").text("Shalom");
          $orderInfo.find(".info .cod span").text(`${code1} / ${code2}`);
          $orderInfo.find(".info .dat1 .ori span").text(origen);
          $orderInfo.find(".info .dat1 .des span").text(destino);
          $orderInfo.find(".content .head .estado-actual").text(mensajeEstado);
          actualizarFases();
        } else {
          alert("No se pudo obtener la información del envío.");
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

// Endpoint Olva
$(document).on(
  "click",
  '#listOrdersShipping .order .cont .actions .btn[data-agency="2"]',
  function () {
    const code1 = $(this).data("code1");
    const code2 = $(this).data("code2");
    const $orderInfo = $("#orderInfo");

    $orderInfo.find(".content .loaders").css("display", "flex");
    $.ajax({
      url: "routes/scrapOlva.php",
      method: "POST",
      data: { numero: code1, codigo: code2 },
      dataType: "json",
      success: function (response) {
        if (response.success && response.data) {
          const generalData = response.data.general;
          const detallesData = response.data.details;

          function actualizarEstado(selector, estado) {
            const fecha = estado?.fecha_creacion;
            const elemento = $orderInfo.find(selector);

            if (fecha) {
              const [year, month, day] = fecha.split("-");
              const formattedDate = `${day}-${month}-${year}`;
              elemento.find(".date").text(formattedDate);
              elemento.show();
            } else {
              elemento.hide();
            }
          }

          detallesData.forEach((estado) => {
            if (estado.estado_tracking === "ENTREGADO") {
              actualizarEstado(".line .fas.entregado", estado);
            } else if (estado.estado_tracking === "ASIGNADO") {
              const estadoAsignado = detallesData.reduce((max, current) => {
                return new Date(max.fecha_creacion) >
                  new Date(current.fecha_creacion)
                  ? max
                  : current;
              });
              actualizarEstado(".line .fas.ruta", estadoAsignado);
            } else if (estado.estado_tracking === "RECEPCION TIENDA") {
              actualizarEstado(".line .fas.agencia", estado);
            }
          });

          const origen = `${generalData.origen || "—"}`;
          const destino = `${generalData.destino || "—"}`;

          $orderInfo.find(".content .head .title span").text("Olva");
          $orderInfo.find(".info .cod span").text(`${code1} - ${code2}`);
          $orderInfo.find(".info .dat1 .ori span").text(origen);
          $orderInfo.find(".info .dat1 .des span").text(destino);
          $orderInfo
            .find(".content .head .estado-actual")
            .text(generalData.nombre_estado_tracking);
          actualizarFases();
        } else {
          alert("No se pudo obtener la información del envío.");
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

// Endpoint Marvisur
$(document).on(
  "click",
  '#listOrdersShipping .order .cont .actions .btn[data-agency="3"]',
  function () {
    const code1 = $(this).data("code1");
    const code2 = $(this).data("code2");
    const $orderInfo = $("#orderInfo");

    $orderInfo.find(".content .loaders").css("display", "flex");

    $.ajax({
      url: "routes/scrapMarvisur.php",
      method: "POST",
      data: { serie: code1, numero: code2 },
      dataType: "json",
      success: function (response) {
        if (response.success && response.data) {
          const detallesData = response.data.Table;

          const actualizarEstado = (selector, fecha) => {
            const elemento = $orderInfo.find(selector);
            if (fecha) {
              const [datePart, timePart] = fecha.split("T");
              const [year, month, day] = datePart.split("-");
              elemento
                .find(".date")
                .text(`${day}-${month}-${year} ${timePart}`);
              elemento.show();
            } else {
              elemento.hide();
            }
          };

          let estadoActualComentario = null;
          detallesData.forEach((estado) => {
            if (estado.COMENTARIO === "ENTREGADO") {
              actualizarEstado(".line .fas.entregado", estado.FECEVENTO);
              estadoActualComentario = "ENTREGADO";
            } else if (estado.COMENTARIO === "EN RUTA") {
              actualizarEstado(".line .fas.ruta", estado.FECEVENTO);
              if (estadoActualComentario !== "ENTREGADO") {
                estadoActualComentario = "EN RUTA";
              }
            } else if (estado.COMENTARIO === "RECEPCION") {
              actualizarEstado(".line .fas.agencia", estado.FECEVENTO);
              if (!estadoActualComentario) {
                estadoActualComentario = "RECEPCION";
              }
            }
          });

          const { DEPORIGEN: origen = "—", DEPDESTINO: destino = "—" } =
            detallesData.find((item) => item.ID === 0) || {};

          $orderInfo.find(".content .head .title span").text("Marvisur");
          $orderInfo.find(".info .cod span").text(`${code1} / ${code2}`);
          $orderInfo.find(".info .dat1 .ori span").text(origen);
          $orderInfo.find(".info .dat1 .des span").text(destino);
          actualizarFases();
          if (estadoActualComentario) {
            $orderInfo
              .find(".content .head .estado-actual")
              .text(estadoActualComentario);
          } else {
            $orderInfo
              .find(".content .head .estado-actual")
              .text("SIN INFORMACIÓN");
          }
        } else {
          alert("No se pudo obtener la información del envío.");
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
