function eliminarPedidosAntiguos() {
  $.ajax({
    url: "routes/deleteOrderShipping.php",
    method: "POST",
    success: function (response) {
      console.log("Respuesta: ", response);
    },
    error: function (xhr, status, error) {
      console.error("Error: ", error);
    },
  });
}
eliminarPedidosAntiguos();

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

$(document).on(
  "click",
  '#listOrdersShipping .order .cont .actions .btn[data-agency="1"]',
  function () {
    const code1 = $(this).data("code1");
    const code2 = $(this).data("code2");
    const $orderInfo = $("#orderInfo");

    // Mostrar el loader
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
              // Si tiene fecha, actualizamos el texto y nos aseguramos de que sea visible
              elemento.find(".date").text(fecha);
              elemento.show(); // Aseguramos que se muestre
            } else {
              // Si no tiene fecha, eliminamos el elemento (lo ocultamos)
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
        } else {
          alert("No se pudo obtener la información del envío.");
        }
      },
      error: function () {
        alert("Error al consultar la guía. Intenta nuevamente.");
      },
      complete: function () {
        // Ocultar el loader cuando la petición se haya completado
        $orderInfo.find(".loaders").css("display", "none");
      },
    });
  }
);

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


$(document).ready(function () {
  $('#registerTrackings .form .ins').on('click', function (e) {
      e.preventDefault();

      const data = {
          order_number: $('#registerTrackings .form #order_number').val(),
          agency: $('#registerTrackings .form #agency').val(),
          code1: $('#registerTrackings .form #code1').val(),
          code2: $('#registerTrackings .form #code2').val(),
          client: {
              document: $('#registerTrackings .form #document').val(),
              name: $('#registerTrackings .form #name').val(),
              phone: $('#registerTrackings .form #phone').val()
          }
      };

      console.log(JSON.stringify(data, null, 2));
  });
});