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
        nameInput.val(name || "");
        phoneInput.val(phone || "");
        return id || null;
      })
      .catch(() => null)
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

    const payload = {
      order_number: orderInput.val(),
      agency_id: form.find("#agency").val(),
      admin_id: 3,
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
          $("#registerTrackings #agency").trigger("change");
          $errorContainer.empty();
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
            });
        }
      });
  });

});



// Listado de Tracking
$(document).ready(function () {
  let isProcessing = false;
  $("#formConsulta").on("submit", function (e) {
    e.preventDefault();
    if (isProcessing) return;
    isProcessing = true;
    const documento = $("#documento").val().trim();
    if (documento !== "70986545") {
      $("#error-message").html("<p>Documento no encontrado</p>").fadeIn().delay(1500).fadeOut(() => { isProcessing = false; });
      return;
    }
    $("#error-message").fadeOut(() => { isProcessing = false; });

    fetch("assets/js/datos.json")
      .then(res => res.json())
      .then(data => {
        if (!data.success || !Array.isArray(data.data)) return;
        const container = $("#listOrdersShipping");
        container.html('<h1 class="title">Mis Pedidos</h1>');
        data.data.forEach(order => {
          const orderHTML = `
            <div class="order">
              <div class="head">
                <p class="orderNum">Orden: <span>${order.order_number}</span></p>
                <p class="track">Tracking: <span>${order.code1} / ${order.code2}</span></p>
                <div class="agencia">
                  <img class="a${order.agency_id}" src="${order.agency_image}" alt="${order.agency}">
                </div>
              </div>
              <div class="cont">
                <div class="info">
                  <p class="fecha">${new Date(order.register_at).toLocaleDateString("es-PE", { year: "numeric", month: "long", day: "numeric" })}</p>
                  <p class="status">${order.status}</p>
                  <p class="name">Nombre: <span>${order.user_name}</span></p>
                  <p class="doc">Documento: <span>${documento}</span></p>
                </div>
                <div class="actions">
                  <button class="btn op" data-code1="${order.code1}" data-code2="${order.code2}" data-agency="${order.agency_id}">Rastrear</button>
                  <a class="btn" href="https://wa.me/51908944969?text=Hola,%20quisiera%20hacer%20una%20consulta%20sobre%20mi%20compra%20con%20número%20de%20orden%3A%20${order.order_number}" target="_blank">Obtener Ayuda</a>
                </div>
              </div>
            </div>
          `;
          container.append(orderHTML);
        });
        container.fadeIn().css("display", "flex");
      })
      .catch(console.error);
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