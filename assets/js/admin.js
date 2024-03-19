$(document).ready(function () {
  const addOrder = $("#addOrder");
  const addOrderOverlay = $("#addOrderOverlay");
  const addOrderSubmit = $("#addOrderSubmit");
  const addOrderMessage = $("#addOrderMessage");
  const modalCancel = $("#modalCancel");
  const modalClose = $("#modalClose");
  const rptOverlay = $("#rpt-ovr");

  const number = $("#number");
  const document = $("#document");
  const documentId = $("#documentId");
  const name = $("#name");
  const comments = $("#ky1-cmm");
  const email = $("#email");
  const phone = $("#phone");

  function message(target, message) {
    target.text(message).slideDown();
  }

  addOrder.on("click", function () {
    addOrderOverlay.fadeToggle();
    rptOverlay.css({
      display: "block",
      "background-color": "rgba(0, 0, 0, 0.7)",
      position: "fixed",
      top: "0",
      left: "0",
      width: "100%",
      height: "100%",
      "z-index": "3",
    });
    number.focus();
  });

  modalClose.add(modalCancel).click(function () {
    addOrderOverlay.fadeToggle();
    rptOverlay.hide();
  });

  document.on("blur", function () {
    const documentValue = $(this).val();

    $.ajax({
      url: "routes/loadUser",
      method: "POST",
      data: { document: documentValue },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          const userData = response.success;
          documentId.val(userData.id);
          name.val(userData.name);
          email.val(userData.email);
          phone.val(userData.phone);
        } else {
          documentId.val("");
          name.val("");
          email.val("");
          phone.val("");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  // addOrderSubmit.submit(function (event) {
  //   event.preventDefault();
  //   addOrderMessage.slideUp();

  //   var number = $("#number").val();
  //   var document = $("#document").val();
  //   var documentId = $("#documentId").val();
  //   var name = $("#name").val();
  //   var comments = $("#ky1-cmm").val();
  //   var email = $("#email").val();
  //   var phone = $("#phone").val();
  //   var machine = $("#ky1-mch").val();
  //   var machineID = $("#ky1-mid").val();
  //   var date = $("#ky1-dte").val();

  //   var formData = new FormData();
  //   formData.append("order", number);
  //   formData.append("document", document);
  //   formData.append("clientID", documentId);
  //   formData.append("client", name);
  //   formData.append("comments", comments);
  //   formData.append("email", email);
  //   formData.append("phone", phone);
  //   formData.append("machine", machine);
  //   formData.append("machineID", machineID);
  //   formData.append("date", date);

  //   $.ajax({
  //     url: "addOrder.php",
  //     method: "POST",
  //     data: formData,
  //     processData: false,
  //     contentType: false,
  //     success: function (response) {
  //       if (response.trim() !== "") {
  //         // Verificar si la respuesta no está vacía
  //         var jsonData = JSON.parse(response);
  //         console.error(jsonData);

  //       } else {
  //         console.error("La respuesta del servidor está vacía");
  //       }
  //     },
  //   });

  //   // let orderNumber = $("#number").val();
  //   // $.ajax({
  //   //   url: "srcOrders.php",
  //   //   method: "POST",
  //   //   data: { orders: orderNumber },
  //   //   success: function (response) {
  //   //     var jsonData = JSON.parse(response);
  //   //     if (jsonData.response === "existe") {
  //   //       // Mostrar mensaje de error si la orden ya existe
  //   //       message(addOrderMessage, "Ya existe una orden con ese número.");
  //   //     } else {
  //   //       // Continuar con el envío del formulario si la orden no existe
  //   //       // Resto del código para enviar el formulario...
  //   //     }
  //   //   },
  //   //   error: function (xhr, status, error) {
  //   //     console.error("Error:", error);
  //   //   },
  //   // });
  // });
  $("#addOrderSubmit").submit(function (event) {
    event.preventDefault();
    $("#addOrderMessage").slideUp();

    let order = $("#number").val().trim();
    let document = $("#document").val().trim();
    let documentId = $("#documentId").val().trim();
    let client = $("#name").val().trim();
    let comments = $("#ky1-cmm").val().trim();
    let email = $("#email").val().trim();
    let changer = $("#changer").val().trim();
    let phone = $("#phone").val().trim();
    let machine = $("#ky1-mch").val().trim();
    let machineID = $("#ky1-mid").val().trim();
    let date = $("#ky1-dte").val().trim();
    let worker = $("select[name='worker']").val().trim();
    let type = $("select[name='type']").val().trim();
    let origin = $("select[name='origin']").val().trim();

    if (
      !validateOrder(order) ||
      !validateDocument(document) ||
      !validateClient(client) ||
      !validateEmail(email) ||
      !validatePhone(phone) ||
      !validateMachine(machine)
    ) {
      return;
    }

    $.ajax({
      url: "addOrder",
      method: "POST",
      data: {
        order: order,
        document: document,
        clientID: documentId,
        client: client,
        comments: comments,
        email: email,
        changer: changer,
        phone: phone,
        machine: machine,
        machineID: machineID,
        date: date,
        worker: worker,
        type: type,
        origin: origin,
      },
      success: function (response) {
        if (response) {
          var jsonData = JSON.parse(response);
          if (jsonData.success) {
            window.location.href = "grid";
          }
        } else {
          console.error("La respuesta del servidor está vacía");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error en la solicitud AJAX:", error);
      },
    });
  });

  function validateOrder(order) {
    if (order === "") {
      displayErrorMessage("El campo 'Orden' es obligatorio.");
      return false;
    }
    $.ajax({
      url: "srcOrders.php",
      method: "POST",
      data: { orders: order },
      dataType: "json",
      success: function (response) {
        if (response.response === "existe") {
          displayErrorMessage("El número de orden ya existe.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al verificar el número de orden:", error);
      },
    });

    return true;
  }

  function validateDocument(document) {
    // Validación de documento (DNI/RUC)
    if (
      !(document.length === 8 || document.length === 11) ||
      !/^\d+$/.test(document)
    ) {
      displayErrorMessage("Ingrese un documento válido (8 o 11 dígitos).");
      return false;
    }
    return true;
  }

  function validateClient(client) {
    if (client === "") {
      displayErrorMessage("El campo 'Cliente' es obligatorio.");
      return false;
    }
    return true;
  }

  function validateEmail(email) {
    // Validación de correo electrónico
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      displayErrorMessage("Ingrese un correo electrónico válido.");
      return false;
    }
    return true;
  }

  function validatePhone(phone) {
    // Validación de número de teléfono con o sin prefijo
    var telefonoRegex = /^(\+\d+)?\d+$/;
    if (!telefonoRegex.test(phone)) {
      displayErrorMessage(
        "Ingrese un número de teléfono válido. (Ejemplo: +51912345678 o 912345678)"
      );
      return false;
    }
    return true;
  }

  function validateMachine(machine) {
    if (machine === "") {
      displayErrorMessage("Seleccione un equipo.");
      return false;
    }
    return true;
  }

  function displayErrorMessage(message) {
    $("#addOrderMessage")
      .html('<div class="error">' + message + "</div>")
      .slideDown();
  }
  var parents = ["one", "two", "thr", "for", "fiv", "six", "sev", "eig", "nin"];
  var parents_n = [
    "Recepción",
    "Ingreso",
    "Revisión",
    "Diagnóstico",
    "Repuesto",
    "Reparación",
    "Pruebas",
    "Listo para Recojo",
    "Entrega",
  ];

  var now_ul;
  var now_ulId;

  var now_li;
  var now_liId;
  var now_open;
  var now_ord;
  var new_ul;
  var new_full;

  var img;

  var rpt_over = $("#rpt-ovr");
  var rpt_mesg = $("#rpt-msg");

  var add_form = $("#add-frm");

  var msg_ordn = $("#msg-ord");
  var msg_imag = $("#msg-img");

  var lst_time = $("#lst-tml");
  var itm_time = $("#itm-tml");

  var add_msg = $("#ky1-frm-msj");

  var err_msg = $("#errorDiv");

  var img_path =
    '<div class="img-flx"><img width="48" height="48" src="assets/img/';

  var today = new Date();
  var year = today.getFullYear();
  var month = String(today.getMonth() + 1).padStart(2, "0");
  var day = String(today.getDate()).padStart(2, "0");

  var fechaActual = year + "-" + month + "-" + day;

  $("#ky1-dte").val(fechaActual);
  $("#ky1-dte").val(fechaActual);

  $(".itm-upd").on("click", function () {
    rpt_over.fadeToggle();
    rpt_mesg.fadeToggle();
    $(".ky1-blr").toggleClass("blr-act");

    now_li = $(this).closest("li");
    now_liId = now_li.data("id");
    now_ord = now_li.data("ord");
    msg_ordn.text(now_liId);

    now_ul = now_li.closest("ul");
    now_ulId = now_ul.data("id");

    img =
      img_path +
      parents[now_ulId - 1] +
      '.svg"/><b>' +
      parents_n[now_ulId - 1] +
      "</b></div>";
    img += img_path + 'r.svg"/></div>';
    img +=
      img_path +
      parents[now_ulId] +
      '.svg"/><b>' +
      parents_n[now_ulId] +
      "</b></div>";

    msg_imag.html(img);
  });

  $("#msg-yes").on("click", function (e) {
    e.preventDefault();
    var notes = $("#msg-cmm").val();
    var changer = $("#msg-chn").val();
    var check = $("#msg-chk").prop("checked");

    $.ajax({
      url: "updOrder.php",
      method: "POST",
      data: {
        now_ord: now_ord,
        now_stt: now_ulId,
        notes: notes,
        changer: changer,
        check: check,
      },
      success: function (response) {
        var jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "grid";
        } else {
          err_msg.text(jsonData.message);
        }
      },
    });
  });

  $("#msg-nop").on("click", function () {
    rpt_over.fadeToggle();
    rpt_mesg.fadeToggle();
    $(".ky1-blr").toggleClass("blr-act");
  });

  $("#msg-cls").on("click", function () {
    rpt_over.fadeToggle();
    rpt_mesg.fadeToggle();
    $(".ky1-blr").toggleClass("blr-act");
  });

  $(".itm-cnt").on("click", function () {
    rpt_over.fadeToggle();
    $(".ky1-blr").toggleClass("blr-act");

    now_li = $(this).closest("li");
    now_liId = now_li.data("id");

    now_ul = now_li.closest("ul");
    now_ulId = now_ul.data("id");

    now_open = itm_time.find(
      'ul[data-id="' + now_ulId + '"] li[data-id="' + now_liId + '"]'
    );

    now_open.fadeToggle();
  });

  $(".itm-cls").on("click", function () {
    rpt_over.fadeToggle();
    now_open.fadeToggle();
    $(".ky1-blr").toggleClass("blr-act");
  });

  $(".workerButton").on("click", function () {
    var usr_dat = $(this).data("usrf");
    $(this).toggleClass("active");
    $('[data-usr="' + usr_dat + '"]').fadeToggle();
  });

  $("#frm-cls").on("click", function () {
    rpt_over.fadeToggle();
    add_form.fadeToggle();
    $(".ky1-blr").toggleClass("blr-act");
  });

  $("#ky1-mch").keyup(function () {
    var machine = $(this).val();
    if (machine.length >= 2) {
      $.ajax({
        type: "POST",
        url: "srcMachine.php",
        data: { machine: machine },
        success: function (data) {
          $("#ky1-sgs").html(data);
          $(".frm-sgs").click(function () {
            var sel = $(this).text();
            var id = $(this).data("id");
            var slug = $(this).data("slug");
            $("#ky1-mch").val(sel);
            $("#ky1-sgs").html("");
            $("#ky1-mim").attr("src", "assets/mac/" + slug + ".webp");
            $("#ky1-mid").val(id);
          });
        },
      });
    } else {
      $("#ky1-sgs").html("");
      $("#ky1-mim").attr("src", "assets/img/def.webp");
      $("#ky1-mid").val("");
    }
  });

  $("#frm-nop").on("click", function () {
    rpt_over.fadeToggle();
    add_form.fadeToggle();
    $(".ky1-blr").toggleClass("blr-act");
  });

  $("#frm-log").submit(function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    err_msg.slideUp();

    $.ajax({
      type: "POST",
      url: "proLogin.php",
      data: formData,
      success: function (response) {
        var jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "grid";
        } else {
          err_msg.text(jsonData.message).slideDown();
        }
      },
    });
  });

  $("#frm-cli").submit(function (e) {
    e.preventDefault();
    var orders = $("#ky1-sor").val();
    var document = $("#ky1-sdc").val();
    err_msg.slideUp();

    if (orders.trim() !== "" && document.trim() !== "") {
      $.ajax({
        type: "POST",
        url: "proClient",
        data: {
          orders: orders,
          document: document,
        },
        success: function (response) {
          var jsonData = JSON.parse(response);
          if (jsonData.success) {
            window.location.href = "order?number=" + orders;
          } else {
            err_msg.text(jsonData.message).slideDown();
          }
        },
      });
    } else {
      // Mostrar un mensaje de error si los campos están vacíos
      err_msg.text("Por favor, completa ambos campos").slideDown();
    }
  });

  const locateOrder = $("#locateOrder");

  locateOrder.on("input", function () {
    const text = $(this).val().toLowerCase();
    $("#lst-tml ul li:has(.ky1-sor)").each(function () {
      const headingText = $(this).find(".ky1-sor").text().toLowerCase();
      if (headingText.includes(text)) {
        $(this).removeClass("ky1-fcs");
      } else {
        $(this).addClass("ky1-fcs");
      }
    });
  });

  var tme_pday = $(".tme-top");

  if (tme_pday) {
    tme_pday.each(function () {
      var dat_pday = $(this).data("stt");
      var dat_prc = dat_pday * 5;
      if (dat_prc > 100) dat_prc = 100;
      $(this).css("left", dat_prc + "%");
    });
  }

  $(".edt-yes").on("click", function (e) {
    e.preventDefault();

    var frm = $(this).closest("form");

    var orders = frm.find(".ky1-oid").val();
    var worker = frm.find(".ky1-wrk").val();
    var type = frm.find(".ky1-typ").val();
    var paid = frm.find(".ky1-pid").val();
    var origin = frm.find(".ky1-ori").val();

    $.ajax({
      url: "updOrderData.php",
      method: "POST",
      data: {
        orders: orders,
        worker: worker,
        type: type,
        paid: paid,
        origin: origin,
      },
      success: function (response) {
        var jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "grid";
        }
      },
    });
  });

  var header = $("#add-tml");
  var headerHeight = header.height();
  var lastScroll = 0;

  $(window).scroll(function () {
    var currentScroll = $(this).scrollTop();

    if (currentScroll > lastScroll) {
      // Scroll hacia abajo
      if (currentScroll > headerHeight) {
        header.addClass("ky1-fxd");
      }
    } else {
      // Scroll hacia arriba
      if (currentScroll <= headerHeight) {
        header.removeClass("ky1-fxd");
      }
    }

    lastScroll = currentScroll;
  });

  const previewInvoice = $("#previewInvoice");
  const invoiceFile = $("#invoiceFile");

  $(".pendingTable .preview").click(function () {
    let fileUrl = $(this).data("src");
    var fileExtension = fileUrl.split(".").pop().toLowerCase();

    if (fileExtension === "pdf") {
      invoiceFile.html(
        '<embed src="' +
          fileUrl +
          '" type="application/pdf" width="100%" height="500px" />'
      );
    } else if (fileExtension.match(/(jpg|jpeg|png|gif)$/)) {
      invoiceFile.html(
        '<img src="' +
          fileUrl +
          '" alt="Vista previa de la imagen" style="max-width: 100%; max-height: 500px;">'
      );
    } else {
      invoiceFile.html(
        "<p>El archivo no es compatible con la previsualización.</p>"
      );
    }
    previewInvoice.show();
  });

  $("#previewInvoice .close").click(function () {
    previewInvoice.hide();
  });

  $(window).click(function (event) {
    if (event.target == previewInvoice[0]) {
      previewInvoice.hide();
    }
  });

  const aproveOverlay = $("#aproveOverlay");

  $(".actionButtons .aprove").click(function () {
    let selectedId = $(this).closest(".actionButtons").data("id");
    aproveOverlay.find(".aproveButtons").attr("data-id", selectedId);
    aproveOverlay.fadeToggle();
  });

  aproveOverlay.find(".modalClose, .modalCancel").click(function () {
    aproveOverlay.fadeToggle();
  });

  $(window).click(function (event) {
    if (event.target == aproveOverlay[0]) {
      aproveOverlay.fadeToggle();
    }
  });

  const aproveSubmit = $("#aproveSubmit");
  const aproveMessage = $("#aproveMessage");

  aproveSubmit.submit(function (event) {
    event.preventDefault();
    aproveMessage.slideUp();

    let scheduleId = aproveOverlay.find(".aproveButtons").data("id");
    let trainingWorker = $("#trainingWorker").val();
    let meet = $("#meet").val();

    if (!validatetrainingWorker(trainingWorker) || !validateMeet(meet)) {
      return;
    }

    let formData = new FormData();
    formData.append("scheduleId", scheduleId);
    formData.append("trainingWorker", trainingWorker);
    formData.append("meet", meet);

    $.ajax({
      url: "routes/updateSchedule",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        console.log(response);
        const jsonData = JSON.parse(response);
        console.log(jsonData);
        if (jsonData.success) {
          window.location.href = "training";
        } else {
          message(aproveMessage, jsonData.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });
  
  const viewOverlay = $("#viewOverlay");
  $(".calendarView .calendarViewRow").click(function () {
    const trainingId = $(this).data("id");
    let formData = new FormData();
    formData.append("trainingId", trainingId);

    $.ajax({
      url: "routes/getTraining",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        const jsonData = JSON.parse(response);
        console.log(jsonData);
        if (jsonData.success) {
          
          const data = jsonData.success;
          viewOverlay.find(".title").text(data.dayName + " " + data.day + " de " + data.month + " a las " + data.schedule);
          viewOverlay.find(".name").text(data.name);
          viewOverlay.find(".model").text(data.model);
          viewOverlay.find(".image").attr("src", "assets/mac/" + data.slug + ".webp");
          viewOverlay.find(".worker").text(data.worker);
          viewOverlay.find(".meet").text(data.meet).attr("href", data.meet);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });

    const buttons = viewOverlay.find(".viewButtons");
    if ($(this).hasClass("finish")){
      buttons.hide();
    } else {
      
      const date = $(this).data("date");
      const start = $(this).data("start");
      const now = new Date();
      const startDate = new Date(date + ' ' + start);
      startDate.setMinutes(startDate.getMinutes() + 90);
      if (now > startDate) {
        cancelTraining.hide();
        finishTraining.show();
      } else {
        finishTraining.hide();
        cancelTraining.show();
      }
      buttons.show();
      buttons.attr("data-id", trainingId);
    }
    
    viewOverlay.fadeToggle();
  });

  viewOverlay.find(".modalClose, .modalCancel").click(function () {
    viewOverlay.fadeToggle();
  });

  $(window).click(function (event) {
    if (event.target == viewOverlay[0]) {
      viewOverlay.fadeToggle();
    }
  });

  const finishTraining = $("#finishTraining");
  const cancelTraining = $("#cancelTraining");
  //const viewTraining = $("#viewTraining");

  finishTraining.click(function () {
    viewMessage.slideUp();
    let trainingId = viewOverlay.find(".viewButtons").data("id");
    let formData = new FormData();
    formData.append("trainingId", trainingId);

    $.ajax({
      url: "routes/finishTraining",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        console.log(response);
        const jsonData = JSON.parse(response);
        console.log(jsonData);
        if (jsonData.success) {
          window.location.href = "training";
        } else {
          message(viewMessage, jsonData.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });
  const viewMessage = $("#viewMessage");
  cancelTraining.click(function () {
    viewMessage.slideUp();
    let trainingId = viewOverlay.find(".viewButtons").data("id");
    let formData = new FormData();
    formData.append("trainingId", trainingId);

    $.ajax({
      url: "routes/cancelTraining",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        console.log(response);
        const jsonData = JSON.parse(response);
        console.log(jsonData);
        if (jsonData.success) {
          window.location.href = "training";
        } else {
          message(viewMessage, jsonData.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  function validatetrainingWorker(trainingWorker) {
    if (trainingWorker.trim() === "") {
      message(aproveMessage, "Seleccione un responsable");
      return false;
    }
    return true;
  }
  function validateMeet(meet) {
    if (meet.trim() === "") {
      message(aproveMessage, "Ingrese un link de Google Meet");
      return false;
    }
    return true;
  }

  const rejectOverlay = $("#rejectOverlay");

  $(".actionButtons .reject").click(function () {
    let selectedId = $(this).closest(".actionButtons").data("id");
    rejectOverlay.find(".rejectButtons").attr("data-id", selectedId);
    let date = $(this).closest(".actionButtons").data("date");
    rejectOverlay.find(".rejectButtons").attr("data-date", date);
    rejectOverlay.fadeToggle();
  });

  rejectOverlay.find(".modalClose, .modalCancel").click(function () {
    rejectOverlay.fadeToggle();
  });

  $(window).click(function (event) {
    if (event.target == rejectOverlay[0]) {
      rejectOverlay.fadeToggle();
    }
  });

  const rejectSubmit = $("#rejectSubmit");
  const rejectMessage = $("#rejectMessage");

  rejectSubmit.submit(function (event) {
    event.preventDefault();
    rejectMessage.slideUp();

    let scheduleId = rejectOverlay.find(".rejectButtons").data("id");
    let date = rejectOverlay.find(".rejectButtons").data("date");
    let rejectText = $("#rejectText").val();

    let formData = new FormData();
    formData.append("scheduleId", scheduleId);
    formData.append("date", date);
    formData.append("rejectText", rejectText);

    $.ajax({
      url: "routes/rejectSchedule",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        var jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "training";
        } else {
          message(rejectMessage, jsonData.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });
  
});
