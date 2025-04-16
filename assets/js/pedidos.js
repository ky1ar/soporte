const SEGUNDOS_EN_UNA_HORA = 3600; 
const SEGUNDOS_EN_24_HORAS = SEGUNDOS_EN_UNA_HORA * 24; 

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

setInterval(eliminarPedidosAntiguos, 5000);

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
          container.html('<h1 class="title">Mis Pedidos</h1>'); // Título principal
          response.orders.forEach((order) => {
            container.append(`
                          <div class="order">
                              <div class="head">
                                  <p class="orderNum">Orden: <span>${order.orden}</span></p>
                                  <p class="agencia">Agencia: <span>${order.nombre_agencia}</span></p>
                              </div>
                              <div class="cont">
                                  <div class="info">
                                      <p class="status">${order.nombre_status}</p>
                                  </div>
                                  <div class="actions">
                                      <!-- Botón de Rastrear con los atributos data-code1 y data-code2 -->
                                      <button class="btn" data-code1="${order.code1}" data-code2="${order.code2}" data-agency="${order.id_agencia}">Rastrear</button>
                                      <a class="btn" href="https://wa.me/51910900581?text=Hola,%20quisiera%20hacer%20una%20consulta%20sobre%20mi%20compra%20con%20número%20de%20orden%3A%20${order.orden}" target="_blank">Obtener Ayuda</a>
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
  $("#listOrdersShipping").on("click", ".actions .btn", function () {
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
