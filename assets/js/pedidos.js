// $("#viewPedidos .shalom #rastreoForm").on("submit", function (e) {
//   e.preventDefault();

//   const $form = $(this);
//   const $resultado = $("#viewPedidos .shalom #resultado");

//   $.ajax({
//     url: "routes/scrapShalom.php",
//     method: "POST",
//     data: $form.serialize(),
//     dataType: "json",
//     success: function (response) {
//       if (response.success) {
//         const data = response.data;
//         $resultado.html(`
//             <p><strong>Número de orden:</strong> ${data.numero_orden}</p>
//             <p><strong>Código de orden:</strong> ${data.codigo_orden}</p>
//             <p><strong>Remitente:</strong> ${data.remitente.nombre}</p>
//             <p><strong>Destinatario:</strong> ${data.destinatario.nombre}</p>
//             <p><strong>Dirección entrega:</strong> ${data.direccion_entrega}</p>
//             <p><strong>Estado de entrega:</strong> ${
//               data.entregado ? "Entregado" : "En tránsito"
//             }</p>
//             <p><strong>Origen:</strong> ${data.origen.departamento} - ${
//           data.origen.distrito
//         }</p>
//             <p><strong>Destino:</strong> ${data.destino.departamento} - ${
//           data.destino.distrito
//         }</p>
//             <p><strong>Contenido:</strong> ${data.contenido}</p>
//             <p><strong>Monto:</strong> S/. ${data.monto}</p>
//             <p><strong>Tiempo estimado:</strong> ${data.tiempo_llegada}</p>
//           `);
//       } else {
//         $resultado.html(`<p style="color:red;">${response.message}</p>`);
//       }
//     },
//     error: function () {
//       $resultado.html(
//         `<p style="color:red;">Error en la consulta. Inténtalo de nuevo.</p>`
//       );
//     },
//   });
// });

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
                                      <p class="fecha">Llega el 2 de mayo</p>
                                      <p class="status">${order.nombre_status}</p>
                                      <p class="details">${order.details}</p>
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

    $.ajax({
      url: "routes/scrapShalom.php",
      method: "POST",
      data: {
        numero: code1,
        codigo: code2,
      },
      dataType: "json",
      success: function (response) {
        if (response.success && response.data) {
          const rastreoData = response.data.rastreo; // Datos de rastreo
          const estadosData = response.data.estados; // Datos de estados
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

          const $orderInfo = $("#orderInfo");
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
    });
  }
);
