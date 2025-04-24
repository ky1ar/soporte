$(document).ready(function () {
  const config = {
    1: { p1: "N° de Orden", m1: 8, type2: "text", p2: "Código de Orden", m2: 4 },
    2: { p1: "N° de Tracking", m1: 7, type2: "select", options: ["25", "24", "23", "22"] },
    3: { p1: "V001", m1: 4, type2: "text", p2: "0000001", m2: 7 },
  };
  $("#registerTrackings .form #agency").on("change", function () {
    const agencyConfig = config[$(this).val()];
    if (!agencyConfig) return;
    const { p1, m1, type2, p2, m2, options } = agencyConfig;
    const $code1 = $("#registerTrackings .form .track #code1");
    $code1.attr({ placeholder: p1, maxlength: m1 }).val("");
    const code2Field = type2 === "select"
      ? `<select class="sp" id="code2" name="code2" required>${options.map(o => `<option value="${o}">${o}</option>`).join("")}</select>`
      : `<input type="text" id="code2" name="code2" placeholder="${p2 || ""}" maxlength="${m2 || 15}" required>`;
  
    $("#registerTrackings .form .track #code2").replaceWith(code2Field);
  }).trigger("change");

  const form = $("#registerTrackings .form");
  const orderInput = form.find("#order_number");
  const documentInput = form.find("#document");
  const nameInput = form.find("#name");
  const phoneInput = form.find("#phone");

  orderInput.on("blur", function () {
    const orderNumber = $(this).val().trim();
    if (!orderNumber) return;
  
    fetch(`https://devintranet.krear3d.com/api/order/id/${orderNumber}`)
      .then((res) => res.ok ? res.json() : Promise.reject())
      .then((data) => {
        const { client, user_order_id } = data?.data || {};
        const disableInputs = client ? true : false;
        documentInput.val(client?.document || "").prop("disabled", disableInputs);
        nameInput.val(client?.name || "").prop("disabled", disableInputs);
        phoneInput.val(client?.phone || "").prop("disabled", disableInputs);
  
        orderInput
          .attr("data-client-id", client?.id || "")
          .attr("data-user-order-id", user_order_id || "");
      })
      .catch(() => {
        documentInput.add(nameInput).add(phoneInput).val("").prop("disabled", false);
        orderInput.removeAttr("data-client-id data-user-order-id");
      });
  });

  documentInput.on("blur", function () {
    const doc = $(this).val().trim();
    if (!doc) return;
  
    fetch(`https://devintranet.krear3d.com/api/user/data/${doc}`)
      .then((res) => res.ok ? res.json() : Promise.reject())
      .then((data) => {
        const { name, phone, id } = data?.data || {};
        if (name) {
          nameInput.val(name).prop("disabled", true);
        }
  
        if (phone) {
          phoneInput.val(phone).prop("disabled", true);
        }
  
        return id || null;
      })
      .catch(() => {
        nameInput.val("").prop("disabled", false);
        phoneInput.val("").prop("disabled", false);
        return null;
      })
      .then((clientId) => {
        orderInput.attr("data-client-id", clientId || "");
      });
  });
  
  

  let isErrorDisplaying = false;

  form.find(".ins").on("click", function (e) {
    e.preventDefault();

    const nativeForm = form[0];
    if (!nativeForm.checkValidity()) {
      nativeForm.reportValidity();
      return;
    }
    if (isErrorDisplaying) return;
    $(this).prop("disabled", true);

    const payload = {
      order_number: orderInput.val(),
      agency_id: form.find("#agency").val(),
      code1: form.find("#code1").val(),
      code2: form.find("#code2").val(),
      client_id: orderInput.attr("data-client-id") || null,
      user_order_id: orderInput.attr("data-user-order-id") || null,
      client: {
        document: documentInput.val(),
        name: nameInput.val(),
        phone: phoneInput.val(),
      },
    };

    fetch("https://devintranet.krear3d.com/api/tracking/add", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(payload),
    })
      .then((res) => res.json())
      .then((data) => {
        const $errorContainer = $("#registerTrackings #error-register");
        if (data.success) {
          form[0].reset();
          orderInput.removeAttr("data-client-id data-user-order-id");
          documentInput.prop("disabled", false);
          nameInput.prop("disabled", false);
          phoneInput.prop("disabled", false);
          setTimeout(() => {
            if (orderInput.val().trim()) orderInput.trigger("blur");
            if (documentInput.val().trim()) documentInput.trigger("blur");
          }, 10);
        
          $("#registerTrackings #agency").trigger("change");
          const $success = $("<p class='true'>")
            .addClass("success-register")
            .text("Registro Exitoso")
            .hide()
            .appendTo($errorContainer.empty())
            .fadeIn(300)
            .delay(1500)
            .fadeOut(300, function () {
              $(this).remove();
            });
          form.find(".ins").prop("disabled", false);
        } else {
          if (!isErrorDisplaying) {
            isErrorDisplaying = true;
            const $p = $("<p>")
              .text(data.data?.message || "Error al registrar el tracking.")
              .hide()
              .appendTo($errorContainer)
              .fadeIn(300)
              .delay(1500)
              .fadeOut(300, function () {
                $(this).remove();
                isErrorDisplaying = false;
                $(form.find(".ins")).prop("disabled", false);
              });
          }
        }
      })
      .catch(() => {
        const $errorContainer = $("#registerTrackings #error-register");
        if (!isErrorDisplaying) {
          isErrorDisplaying = true;
          const $p = $("<p>")
            .text("Error inesperado. Intente nuevamente.")
            .hide()
            .appendTo($errorContainer)
            .fadeIn(300)
            .delay(1500)
            .fadeOut(300, function () {
              $(this).remove();
              isErrorDisplaying = false;
              $(form.find(".ins")).prop("disabled", false);
            });
        }
      });
  });
});



// Listado de Tracking
// $(document).ready(function () {
//   let isProcessing = false;

//   $("#formConsulta").on("submit", function (e) {
//     e.preventDefault();
//     if (isProcessing) return;
//     isProcessing = true;
//     const documento = $("#documento").val().trim();
//     if (!documento) {
//       $("#error-message").html("<p>Por favor, ingresa un documento válido</p>").fadeIn().delay(1500).fadeOut(() => { isProcessing = false; });
//       return;
//     }
//     $("#error-message").fadeOut();
//     fetch("https://devintranet.krear3d.com/api/tracking/list", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/json"
//       },
//       body: JSON.stringify({ document: documento })
//     })
//     .then(res => res.json())
//     .then(data => {
//       if (!data.success || !Array.isArray(data.data) || data.data.length === 0) {
//         $("#error-message").html("<p>Documento no encontrado</p>").fadeIn().delay(1500).fadeOut(() => { isProcessing = false; });
//         return;
//       }
//       const container = $("#listOrdersShipping");
//       container.html('<h1 class="title">Mis Pedidos</h1>');
//       data.data.forEach(order => {
//         const orderHTML = `
//           <div class="order">
//             <div class="head">
//               <p class="orderNum">Orden: <span>${order.order_number}</span></p>
//               <p class="track">Tracking: <span>${order.code1} / ${order.code2}</span></p>
//               <div class="agencia">
//                 <img src="${order.agency_image}" alt="logo-agencia">
//               </div>
//             </div>
//             <div class="cont">
//               <div class="info">
//                 <p class="fecha">--</p>
//                 <p class="status">${order.status}</p>
//                 <p class="name">Nombre: <span>${order.client_name}</span></p>
//                 <p class="doc">Documento: <span>${order.client_document}</span></p>
//               </div>
//               <div class="actions">
//                 <button class="btn op" data-trackingId="${order.id}">Rastrear</button>
//                 <a class="btn" href="https://wa.me/51908944969?text=Hola,%20quisiera%20hacer%20una%20consulta%20sobre%20mi%20compra%20con%20número%20de%20orden%3A%20${order.order_number}" target="_blank">Obtener Ayuda</a>
//               </div>
//             </div>
//           </div>
//         `;
//         container.append(orderHTML);
//       });
//       container.fadeIn().css("display", "flex");
//       isProcessing = false;
//     })
//     .catch(error => {
//       console.error(error);
//       $("#error-message").html("<p>Error al consultar el documento</p>").fadeIn().delay(1500).fadeOut(() => { isProcessing = false; });
//     });
//   });
// });

// nuevo2 
$(document).ready(function () {
  let isProcessing = false;

  // Verificar si hay un documento guardado en cache
  const cachedDocument = localStorage.getItem("tracking_document");

  if (cachedDocument) {
    // Si hay un documento guardado, hacemos el submit automáticamente
    $("#documento").val(cachedDocument);  // Rellenar el formulario con el documento guardado
    $("#formConsulta").submit();  // Realizar el submit automáticamente
  }

  $("#formConsulta").on("submit", function (e) {
    e.preventDefault();
    if (isProcessing) return;
    isProcessing = true;
    const documento = $("#documento").val().trim();
    if (!documento) {
      $("#error-message").html("<p>Por favor, ingresa un documento válido</p>").fadeIn().delay(1500).fadeOut(() => { isProcessing = false; });
      return;
    }
    $("#error-message").fadeOut();

    // Guardar solo el documento en el cache (localStorage)
    localStorage.setItem("tracking_document", documento);

    // Realizamos la consulta
    fetch("https://devintranet.krear3d.com/api/tracking/list", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ document: documento })
    })
    .then(res => res.json())
    .then(data => {
      if (!data.success || !Array.isArray(data.data) || data.data.length === 0) {
        $("#error-message").html("<p>Documento no encontrado</p>").fadeIn().delay(1500).fadeOut(() => { isProcessing = false; });
        return;
      }

      // Mostrar los pedidos
      mostrarPedidos(data);
      isProcessing = false;
    })
    .catch(error => {
      console.error(error);
      $("#error-message").html("<p>Error al consultar el documento</p>").fadeIn().delay(1500).fadeOut(() => { isProcessing = false; });
    });
  });

  // Función para mostrar los pedidos en el DOM
  function mostrarPedidos(data) {
    const container = $("#listOrdersShipping");
    container.html('<h1 class="title">Mis Pedidos</h1>');
    data.data.forEach(order => {
      const orderHTML = `
        <div class="order">
          <div class="head">
            <p class="orderNum">Orden: <span>${order.order_number}</span></p>
            <p class="track">Tracking: <span>${order.code1} / ${order.code2}</span></p>
            <div class="agencia">
              <img src="${order.agency_image}" alt="logo-agencia">
            </div>
          </div>
          <div class="cont">
            <div class="info">
              <p class="fecha">--</p>
              <p class="status">${order.status}</p>
              <p class="name">Nombre: <span>${order.client_name}</span></p>
              <p class="doc">Documento: <span>${order.client_document}</span></p>
            </div>
            <div class="actions">
              <button class="btn op" data-trackingId="${order.id}">Rastrear</button>
              <a class="btn" href="https://wa.me/51908944969?text=Hola,%20quisiera%20hacer%20una%20consulta%20sobre%20mi%20compra%20con%20número%20de%20orden%3A%20${order.order_number}" target="_blank">Obtener Ayuda</a>
            </div>
          </div>
        </div>
      `;
      container.append(orderHTML);
    });
    container.fadeIn().css("display", "flex");
  }
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

// Estados en barra
function actualizarFases(lastStatusId) {
  const $fases = $("#orderInfo .content .status .fases");
  $fases.find(".st, .bar").removeClass("activo");
  if (lastStatusId >= 1) {
    $fases.find(".st.one").addClass("activo");
  }
  if (lastStatusId >= 2) {
    $fases.find(".bar.one").addClass("activo");
    $fases.find(".st.two").addClass("activo");
  }
  if (lastStatusId >= 3) {
    $fases.find(".bar.two").addClass("activo");
    $fases.find(".st.tree").addClass("activo");
  }
}

// Endpoint para consultar los estados en modal
$(document).on("click", '#listOrdersShipping .order .cont .actions .btn.op', function () {
  const trackingId = $(this).data("trackingid");
  const $orderInfo = $("#orderInfo");
  $orderInfo.find(".content .loaders").css("display", "flex");

  fetch(`https://devintranet.krear3d.com/api/tracking/id/${trackingId}`)
    .then((res) => res.json())
    .then((response) => {
      if (response.success && response.data) {
        const data = response.data;
        $orderInfo.find(".content .head .title span").text(data.agency_name || "—");
        $orderInfo.find(".info .cod span").text(`${data.code1} / ${data.code2}`);
        $orderInfo.find(".info .dat1 .ori span").text(data.origin_agency || "—");
        $orderInfo.find(".info .dat1 .des span").text(data.destination_agency || "—");
        $orderInfo.find(".content .head .estado-actual").text(data.last_status_name || "—");

        function actualizarEstado(selector, texto) {
          const elemento = $orderInfo.find(selector);
          if (texto) {
            elemento.find(".date").text(texto);
            elemento.show();
          } else {
            elemento.hide();
          }
        }
        
        const history = data.status_history || [];
        actualizarEstado(".line .fas.agencia", null);
        actualizarEstado(".line .fas.ruta", null);
        actualizarEstado(".line .fas.entregado", null);
        history.forEach((estado) => {
          const { status_name, register_at } = estado;
        
          if (status_name === "Agencia") {
            actualizarEstado(".line .fas.agencia", register_at);
          } else if (status_name === "En ruta") {
            actualizarEstado(".line .fas.ruta", register_at);
          } else if (status_name === "Entregado") {
            actualizarEstado(".line .fas.entregado", register_at);
          }
        });
        actualizarFases(data.last_status_id);
      } else {
        alert("No se pudo obtener la información del envío.");
      }
    })
    .catch(() => {
      alert("Error al consultar la guía. Intenta nuevamente.");
    })
    .finally(() => {
      $orderInfo.find(".loaders").css("display", "none");
    });
});
