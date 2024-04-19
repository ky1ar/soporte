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

  addOrderSubmit.submit(function (event) {
    event.preventDefault();
    addOrderMessage.slideUp();

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
      url: "routes/addOrder",
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
          let jsonData = JSON.parse(response);
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
      url: "routes/srcOrders",
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
    if (phone === "") {
      displayErrorMessage("Ingrese un número de teléfono");
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
    addOrderMessage
      .html('<div class="error">' + message + "</div>")
      .slideDown();
  }
  let parents = ["one", "two", "thr", "for", "fiv", "six", "sev", "eig", "nin"];
  let parents_n = [
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

  let now_ul;
  let now_ulId;

  let now_li;
  let now_liId;
  let now_open;
  let now_ord;
  let img;

  let rpt_over = $("#rpt-ovr");
  let rpt_mesg = $("#rpt-msg");

  let add_form = $("#add-frm");

  let msg_ordn = $("#msg-ord");
  let msg_imag = $("#msg-img");

  let itm_time = $("#itm-tml");
  let err_msg = $("#errorDiv");

  let img_path =
    '<div class="img-flx"><img width="48" height="48" src="assets/img/';

  let today = new Date();
  let year = today.getFullYear();
  let month = String(today.getMonth() + 1).padStart(2, "0");
  let day = String(today.getDate()).padStart(2, "0");

  let fechaActual = year + "-" + month + "-" + day;

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
    let notes = $("#msg-cmm").val();
    let changer = $("#msg-chn").val();
    let check = $("#msg-chk").prop("checked");

    $.ajax({
      url: "routes/updOrder",
      method: "POST",
      data: {
        now_ord: now_ord,
        now_stt: now_ulId,
        notes: notes,
        changer: changer,
        check: check,
      },
      success: function (response) {
        let jsonData = JSON.parse(response);
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
    let usr_dat = $(this).data("usrf");
    $(this).toggleClass("active");
    $('[data-usr="' + usr_dat + '"]').fadeToggle();
  });

  $("#frm-cls").on("click", function () {
    rpt_over.fadeToggle();
    add_form.fadeToggle();
    $(".ky1-blr").toggleClass("blr-act");
  });

  $("#ky1-mch").keyup(function () {
    let machine = $(this).val();
    if (machine.length >= 2) {
      $.ajax({
        type: "POST",
        url: 'routes/srcMachine',
        data: { machine: machine },
        success: function (data) {
          $("#ky1-sgs").html(data);
          $(".frm-sgs").click(function () {
            let sel = $(this).text();
            let id = $(this).data("id");
            let slug = $(this).data("slug");
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

  $("#loginForm").submit(function (e) {
    e.preventDefault();
    let formData = $(this).serialize();
    err_msg.slideUp();

    $.ajax({
      type: "POST",
      url: "routes/proLogin",
      data: formData,
      success: function (response) {
        let jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "grid";
        } else {
          err_msg.text(jsonData.message).slideDown();
        }
      },
    });
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

  let tme_pday = $(".tme-top");

  if (tme_pday) {
    tme_pday.each(function () {
      let dat_pday = $(this).data("stt");
      let dat_prc = dat_pday * 5;
      if (dat_prc > 100) dat_prc = 100;
      $(this).css("left", dat_prc + "%");
    });
  }

  $('.tbl-tec').on('click', function() {

    rpt_over.fadeToggle();
    $('.ky1-blr').toggleClass('blr-act');

    const now_li = $(this).closest('tr');
    const now_liId = now_li.data('id');
    
    now_open = itm_time.find('li[data-id="' + (now_liId) + '"]');

    now_open.fadeToggle();
});

  $(".edt-yes").on("click", function (e) {
    e.preventDefault();

    let frm = $(this).closest("form");

    let orders = frm.find(".ky1-oid").val();
    let worker = frm.find(".ky1-wrk").val();
    let type = frm.find(".ky1-typ").val();
    let paid = frm.find(".ky1-pid").val();
    let origin = frm.find(".ky1-ori").val();

    $.ajax({
      url: "routes/updOrderData",
      method: "POST",
      data: {
        orders: orders,
        worker: worker,
        type: type,
        paid: paid,
        origin: origin,
      },
      success: function (response) {
        let jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "grid";
        }
      },
    });
  });

  let header = $("#add-tml");
  let headerHeight = header.height();
  let lastScroll = 0;

  $(window).scroll(function () {
    let currentScroll = $(this).scrollTop();

    if (currentScroll > lastScroll) {
      // Scroll hacia abajo
      if (currentScroll > headerHeight) {
        header.addClass("ky1-fxd");
      }
    } else if (currentScroll <= headerHeight) {
        header.removeClass("ky1-fxd");
    }

    lastScroll = currentScroll;
  });

  const previewInvoice = $("#previewInvoice");
  const invoiceFile = $("#invoiceFile");
  const viewOverlay = $("#viewOverlay");
  const viewInvoice = $("#viewInvoice");

  viewInvoice.click(function () {
    let fileUrl = $(this).attr('data-src');
    let fileExtension = fileUrl.split(".").pop().toLowerCase();
    if (fileExtension === "pdf") {
      invoiceFile.html(
        '<embed src="' +
          fileUrl +
          '" type="application/pdf" height="800px" width="800px" />'
      );
    } else {
      invoiceFile.html(
        '<img src="' +
          fileUrl +
          '" alt="Vista previa de la imagen" style="width: 100%; height: 800px;">'
      );
    }
    previewInvoice.fadeToggle();
    $("#viewOverlay").addClass('blur');
  });

  $("#previewInvoice .close").click(function () {
    previewInvoice.fadeToggle();
    $("#viewOverlay").removeClass('blur');

  });

  $(window).click(function (event) {
    if (event.target == previewInvoice[0]) {
      previewInvoice.fadeToggle();
    $("#viewOverlay").removeClass('blur');

    }
  });

  const $aproveTraining = $("#aproveTraining");
  const actionMessage = $("#actionMessage");
  const $trainingWorker = $("#trainingWorker");
  const $meetLink = $("#meetLink");

  $aproveTraining.click(function (event) {
    event.preventDefault();
    actionMessage.slideUp();

    let scheduleId = $actionButtons.data("id");
    let trainingWorker = $trainingWorker.val();
    let meetLink = $meetLink.val();

    if (!validatetrainingWorker(trainingWorker) || !validateMeet(meetLink)) {
      return;
    }

    let formData = new FormData();
    formData.append("scheduleId", scheduleId);
    formData.append("trainingWorker", trainingWorker);
    formData.append("meet", meetLink);

    $.ajax({
      url: "routes/updateSchedule",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        const jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "training";
        } else {
          message(actionMessage, jsonData.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  const $rejectTraining = $("#rejectTraining");

  $rejectTraining.click(function (event) {
    event.preventDefault();
    actionMessage.slideUp();

    let scheduleId = $actionButtons.data("id");
    let date = $actionButtons.data("date");

    let formData = new FormData();
    formData.append("scheduleId", scheduleId);
    formData.append("date", date);

    $.ajax({
      url: "routes/rejectSchedule",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        let jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "training";
        } else {
          message(actionMessage, jsonData.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  const calendarTable = $("#calendarTable");

  const $actionButtons  = $("#viewTraining .actionButtons");
  const $viewButtons    = $("#viewTraining .viewButtons");

  calendarTable.on("click", ".calendarViewRow", function () {
    let trainingId = $(this).data("id");
    let level = $("#upd_worker").data("level");
    let formData = new FormData();
    formData.append("trainingId", trainingId);

    const $date        = $("#viewTraining .date");
    const $worker      = $("#viewTraining .worker");
    const $id_worker   = $("#viewTraining .id_worker");
    const $upd_worker  = $("#upd_worker");
    const $schedule    = $("#viewTraining .schedule");
    const $model       = $("#viewTraining .model");
    const $count       = $("#viewTraining .count");
    const $name        = $("#viewTraining .name");
    const $invoice     = $("#viewTraining .invoice");
    const $admin      = $("#viewTraining .admin");
    const $document    = $("#viewTraining .document");
    const $email       = $("#viewTraining .email");
    const $phone       = $("#viewTraining .phone");
    const $meet        = $("#viewTraining .meet");
    const $upd_meet    = $("#viewTraining .upd_meet");
    const $image       = $("#viewTraining .image");
    const $pre         = $("#viewTraining .pre");

    const $staticWorker   = $("#viewTraining .staticWorker");
    const $editableWorker = $("#viewTraining .editableWorker");
    const $staticMeet     = $("#viewTraining .staticMeet");
    const $editableMeet   = $("#viewTraining .editableMeet");
    
    $.ajax({
      url: "routes/getTraining",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        const jsonData = JSON.parse(response);
        if (jsonData.success) {
          const data = jsonData.success;
          $date.text(data.dayName+" "+data.day+" de "+data.month);

          $staticWorker.hide();
          $staticMeet.hide();
          $editableWorker.hide();
          $editableMeet.hide();
          $actionButtons.hide();
          $viewButtons.hide();
          $upd_worker.hide();

          if (level >= 3) {
            if (data.t_state == 0) {
              $editableWorker.show();
              $editableMeet.show();
              $actionButtons.show();
              $actionButtons.attr("data-id", trainingId);
              $actionButtons.attr("data-date", data.date);
            } else if(data.t_state == 1) {
              $staticMeet.show();
              $editableWorker.show();
              $viewButtons.show();
              $upd_worker.show();
              $viewButtons.attr("data-id", trainingId);
            } else {
              $staticWorker.show();
              $staticMeet.show();
            }
          } else {
            $staticWorker.show();
            $staticMeet.show();
          }

          $schedule.text(data.schedule);
          $model.text(data.model);
          $count.text("(" + data.count + ")");
          $name.text(data.name);
          if(data.admin) {
            $invoice.hide();
            $admin.show();
          } else {
            $invoice.show();
            $admin.hide();
            $invoice.attr("data-src", data.invoice);
          }
          $document.text(data.document);
          $email.text(data.email);
          $phone.text("+" + data.phone).attr("href", "https://api.whatsapp.com/send?phone="+data.phone);
          $meet.text(data.meet).attr("href", data.meet);
          $upd_meet.val(data.meet).focus();
          $image.attr("src", "assets/mac/" + data.slug + ".webp");
          $worker.text(data.worker);
          $id_worker.val(data.id_worker);
          $pre.val(trainingId);

          $("#topBar").addClass('blur');
          $("#navigationBar").addClass('blur');
          $("#adminSection").addClass('blur');
          viewOverlay.fadeToggle();
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  viewOverlay.find(".modalClose").click(function () {
    viewOverlay.fadeToggle();
    $("#topBar").removeClass('blur');
    $("#navigationBar").removeClass('blur');
    $("#adminSection").removeClass('blur');
  });

  $(window).click(function (event) {
    if (event.target == viewOverlay[0]) {
      viewOverlay.fadeToggle();
      $("#topBar").removeClass('blur');
      $("#navigationBar").removeClass('blur');
      $("#adminSection").removeClass('blur');
    }
  });

  const finishTraining = $("#finishTraining");
  const cancelTraining = $("#cancelTraining");

  finishTraining.click(function () {
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
        const jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "training";
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  cancelTraining.click(function () {
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
        const jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "training";
        } 
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  function validatetrainingWorker(trainingWorker) {
    if (trainingWorker.trim() === "") {
      message(actionMessage, "Seleccione un responsable");
      return false;
    }
    return true;
  }
  function validateMeet(meet) {
    if (meet.trim() === "") {
      message(actionMessage, "Δ Ingrese un link de Google Meet Δ");
      return false;
    }
    return true;
  }
  

  let currentDate = new Date();
  let months = [
    "enero",
    "febrero",
    "marzo",
    "abril",
    "mayo",
    "junio",
    "julio",
    "agosto",
    "septiembre",
    "octubre",
    "noviembre",
    "diciembre",
  ];

  const calendarPrev = $("#calendarPrev");
  const calendarNext = $("#calendarNext");
  const loadingResponse = $("#loadingResponse");
  const monthName = $("#monthName");

  calendarPrev.click(function () {
    if ($(this).hasClass("admin")) {
      loadCalendar(-1);
    } else {
      let offsetMonth = currentDate.getMonth() - 1;
      if (offsetMonth > today.getMonth()) {
        loadCalendar(-1);
        calendarNext.removeClass("disabled");
      } else if (offsetMonth == today.getMonth()) {
        loadCalendar(-1);
        $(this).addClass("disabled");
      }
    }
  });

  calendarNext.click(function () {
    if ($(this).hasClass("admin")) {
      loadCalendar(1);
    } else {
      let offsetMonth = currentDate.getMonth() + 1;
      let maxMonth = today.getMonth() + 2;
      if (offsetMonth < maxMonth) {
        loadCalendar(1);
        calendarPrev.removeClass("disabled");
      } else if (offsetMonth == maxMonth) {
        loadCalendar(1);
        $(this).addClass("disabled");
      }
    }
  });

  function loadCalendar(offset) {
    loadingResponse.show();
    currentDate.setMonth(
      offset === 0 ? today.getMonth() : currentDate.getMonth() + offset
    );
    currentDate.setDate(1);

    let formatedDate =
      currentDate.getFullYear() +
      "-" +
      ("0" + (currentDate.getMonth() + 1)).slice(-2) +
      "-" +
      ("0" + currentDate.getDate()).slice(-2);
    let splitDate = formatedDate.split("-");
    let month = splitDate[1];
    month = months[parseInt(month, 10) - 1];
    let firstDayNum = currentDate.getDay();
    $.ajax({
      url: "routes/loadCalendarAdmin",
      method: "POST",
      data: { date: formatedDate, day: firstDayNum },
      success: function (response) {
        calendarTable.html(response);
        monthName.text(month + " " + currentDate.getFullYear());
        loadingResponse.hide();
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        loadingResponse.hide();
      },
    });
  }

  $("#upd_worker").on("click", function (e) {
    let id_worker = $(".id_worker").val();
    let pre = $(".pre").val();

    let datos = new FormData();
    datos.append("id_worker", id_worker);
    datos.append("pre", pre);

    $.ajax({
      url: "routes/updTraining",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (response) {
        if (response.success ) {
          window.location.href = "training";
        }
      },
    });
  });

  const AddOverlay = $("#AddOverlay");
  const scheduleSelector = $("#scheduleSelector");
  const scheduleForm = $("#scheduleForm");

  calendarTable.on("click", ".calendarAdd", function () {
    let dayNumber = $(this).attr("data-day");
    let temporal = currentDate;
    temporal.setDate(dayNumber);
    let formatedDate =
      temporal.getFullYear() +
      "-" +
      ("0" + (temporal.getMonth() + 1)).slice(-2) +
      "-" +
      ("0" + temporal.getDate()).slice(-2);
    $.ajax({
      url: "routes/loadSchedule",
      method: "POST",
      data: { date: formatedDate },
      success: function (response) {
        let jsonData = JSON.parse(response);
        scheduleSelector.html(jsonData.html);
        scheduleSelector.show();
        scheduleForm.hide();

        $("#topBar").addClass('blur');
        $("#navigationBar").addClass('blur');
        $("#adminSection").addClass('blur');
        AddOverlay.fadeToggle();
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });

    
  });

  AddOverlay.find(".modalClose").click(function () {
    AddOverlay.fadeToggle();
    $("#topBar").removeClass('blur');
    $("#navigationBar").removeClass('blur');
    $("#adminSection").removeClass('blur');
  });

  $(window).click(function (event) {
    if (event.target == AddOverlay[0]) {
      AddOverlay.fadeToggle();
      $("#topBar").removeClass('blur');
      $("#navigationBar").removeClass('blur');
      $("#adminSection").removeClass('blur');
    }
  });

  const $addTraining = $("#addTraining");
  const machine = $("#machine");
  const suggestions = $("#suggestions");
  const machineImage = $("#machineImage");
  const machineId = $("#machineId");

  const selectedSchedule = $("#selectedSchedule");
  const picked = $("#picked");

  const scheduleSubmit = $("#scheduleSubmit");
  const scheduleFormMessage = $("#scheduleFormMessage");
  const scheduleCalendar = $("#scheduleCalendar");

  $addTraining.on("click", ".boxSchedule", function () {
    const selectedData = $("#selectedData");
    const dateAvailable = $("#dateAvailable");
    let day = selectedData.data("day");
    let date = selectedData.data("date");
    let count = dateAvailable.val();
    let schedule = $(this).data("schedule");

    selectedSchedule.text(day + " - " + schedule);
    picked.val(date);
    picked.attr("data-schedule", schedule);
    picked.attr("data-count", count);

    scheduleSelector.hide();
    scheduleForm.show();
  });

  machine.keyup(function () {
    let machineVal = $(this).val();
    if (machineVal.length >= 2) {
      $.ajax({
        url: "routes/loadMachine",
        type: "POST",
        data: { machineVal: machineVal },
        success: function (data) {
          suggestions.html(data);
          $(".suggestionsRow").click(function () {
            let sel = $(this).text();
            let id = $(this).data("id");
            let slug = $(this).data("slug");
            machine.val(sel);
            suggestions.html("");
            machineImage.attr("src", "assets/mac/" + slug + ".webp");
            machineId.val(id);
          });
        },
      });
    } else {
      suggestions.html("");
      machineImage.attr("src", "assets/img/def.webp");
      machineId.val("");
    }
  });

  scheduleSubmit.submit(function (event) {
    console.log('paso 0');

    event.preventDefault();
    scheduleFormMessage.slideUp();

    let date = picked.val();
    let schedule = picked.data("schedule");
    let count = picked.data("count");
    console.log('paso 1');

    let dniRUC = $("#dniRUC").val();
    let client = $("#client").val();
    let email = $("#email").val();
    let phone = $("#phone").val();
    let machine = machineId.val();
    let meet = $("#meet").val();
    console.log('paso 2' + meet + ' test ');

    if (
      !validateDniRuc(dniRUC) ||
      !validateClientAdmin(client) ||
      !validateEmailAdmin(email) ||
      !validatePhoneAdmin(phone) ||
      !validateMachineId(machine) ||
      !validateMeetAdmin(meet)
    ) {
      return;
    }

    let formData = new FormData();
    formData.append("schedule", schedule);
    formData.append("date", date);
    formData.append("count", count);
    formData.append("dniRUC", dniRUC);
    formData.append("client", client);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("machineId", machine);
    formData.append("meet", meet);
    $.ajax({
      url: "routes/registerScheduleAdmin",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        const jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "training";
        } else {
          message(actionMessage, jsonData.error);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  function validateDniRuc(dniRUC) {
    const regex = /^(KREAR\*3D|\d{8}|\d{11})$/;

    if (!dniRUC.trim() || !regex.test(dniRUC.trim())) {
        message(scheduleFormMessage, "Ingrese un documento válido (DNI o RUC)");
        return false;
    }

    return true;
  }
  function validateClientAdmin(client) {
    if (client.trim() === "") {
      message(scheduleFormMessage, "El campo del nombre no puede estar vacío");
      return false;
    }
    return true;
  }
  function validateEmailAdmin(email) {
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      message(scheduleFormMessage, "Ingrese un correo electrónico válido");
      return false;
    }
    return true;
  }
  function validatePhoneAdmin(phone) {
    if (phone === "") {
      message(scheduleFormMessage, "Ingrese un número de teléfono");
      return false;
    }
    return true;
  }
  function validateMeetAdmin(meet) {
    if (meet.trim() === "") {
      message(scheduleFormMessage, "Δ Ingrese un link de Google Meet Δ");
      return false;
    }
    return true;
  }
  function validateMachineId(machineId) {
    if (machineId.trim() === "") {
      message(scheduleFormMessage, "Seleccione un equipo");
      return false;
    }
    return true;
  }


});
