$(document).ready(function () {
  const searchOrderMessage = $("#searchOrderMessage");
  const searchOrder = $("#searchOrder");

  const calendarPrev = $("#calendarPrev");
  const calendarNext = $("#calendarNext");
  const calendarTable = $("#calendarTable");
  const monthName = $("#monthName");
  const loadingResponse = $("#loadingResponse");

  const machine = $("#machine");
  const suggestions = $("#suggestions");
  const machineImage = $("#machineImage");
  const machineId = $("#machineId");

  const selectedSchedule = $("#selectedSchedule");
  const picked = $("#picked");

  const scheduleSubmit = $("#scheduleSubmit");
  const scheduleFormMessage = $("#scheduleFormMessage");
  const scheduleCalendar = $("#scheduleCalendar");

  function message(target, message) {
    target.text(message).slideDown();
  }

  // Función para manejar el clic en los enlaces del menú
  $("section.menu-wiki ul li ul li a").on("click", function (event) {
    event.preventDefault();
    var title = $(this).text();
    console.log(title);
    $.ajax({
      url: "routes/getWiki.php",
      type: "POST",
      data: { title: title },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          var data = response.data;
          mostrarDatos(data);
        } else {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error en la solicitud AJAX: ", error);
      },
    });
  });

  function mostrarDatos(data) {
    var content = "<div>";

    if (data.fecha_creacion) {
      let fecha = new Date(data.fecha_creacion);
      let fechaFormateada = `${String(fecha.getDate()).padStart(2, "0")} de ${
        months[fecha.getMonth()]
      } del ${fecha.getFullYear()}`;
      content += `<p>${fechaFormateada}</p>`;
    }

    if (data.tit_father || data.title) {
      content += `<h1>${data.tit_father ? data.tit_father : ""} -> ${
        data.title
      }</h1>`;
    }

    if (data.img) {
      content += `<img src="assets/img/wiki-images/${data.img}" alt="">`;
    }
    content += "</div><div>";
    if (data.sub1) {
      content += `<h1>${data.sub1}</h1>`;
    }
    if (data.p1) {
      content += `<p>${data.p1}</p>`;
    }
    if (data.img1) {
      content += `<img src="${data.img1}" alt="">`;
    }
    if (data.sub2) {
      content += `<h1>${data.sub2}</h1>`;
    }
    if (data.p2) {
      content += `<p>${data.p2}</p>`;
    }
    // if (data.img2) {
    //   content += `<img src="${data.img2}" alt="">`;
    // }
    if (data.sub3) {
      content += `<h1>${data.sub3}</h1>`;
    }
    if (data.p3) {
      content += `<p>${data.p3}</p>`;
    }
    if (data.img3) {
      content += `<img src="${data.img3}" alt="">`;
    }
    if (data.sub4) {
      content += `<h1>${data.sub4}</h1>`;
    }
    if (data.p4) {
      content += `<p>${data.p4}</p>`;
    }
    if (data.img4) {
      content += `<img src="${data.img4}" alt="">`;
    }
    if (data.sub5) {
      content += `<h1>${data.sub5}</h1>`;
    }
    if (data.p5) {
      content += `<p>${data.p5}</p>`;
    }
    if (data.img5) {
      content += `<img src="${data.img5}" alt="">`;
    }
    content += "</div>";
    $(".data").html(content);
  }

  searchOrder.submit(function (event) {
    event.preventDefault();
    searchOrderMessage.slideUp();

    let orderNumber = $("#orderNumber").val();
    let document = $("#document").val();

    if (!validateorderNumber(orderNumber) || !validateDocument(document)) {
      return;
    }

    $.ajax({
      type: "POST",
      url: "routes/searchOrder",
      data: {
        orderNumber: orderNumber,
        document: document,
      },
      success: function (response) {
        let jsonData = JSON.parse(response);
        if (jsonData.success) {
          window.location.href = "order?number=" + orderNumber;
        } else {
          message(searchOrderMessage, jsonData.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  });

  let currentDate = new Date();
  let today = new Date();
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

  calendarPrev.click(function () {
    let offsetMonth = currentDate.getMonth() - 1;
    if (offsetMonth > today.getMonth()) {
      loadCalendar(-1);
      calendarNext.removeClass("disabled");
    } else if (offsetMonth == today.getMonth()) {
      loadCalendar(-1);
      $(this).addClass("disabled");
    }
  });

  calendarNext.click(function () {
    let offsetMonth = currentDate.getMonth() + 1;
    let maxMonth = today.getMonth() + 2;
    if (offsetMonth < maxMonth) {
      loadCalendar(1);
      calendarPrev.removeClass("disabled");
    } else if (offsetMonth == maxMonth) {
      loadCalendar(1);
      $(this).addClass("disabled");
    }
  });

  const calendarNavigation = $("#calendarNavigation");
  const calendarBackDiv = $("#calendarBackDiv");
  const calendarBack = $("#calendarBack");
  const calendarSelector = $("#calendarSelector");
  const scheduleSelector = $("#scheduleSelector");
  const scheduleForm = $("#scheduleForm");

  calendarBack.click(function () {
    scheduleSelector.hide();
    calendarBackDiv.hide();
    scheduleForm.hide();

    calendarSelector.show();
    calendarNavigation.show();
    loadCalendar(0);
    calendarPrev.addClass("disabled");
    calendarNext.removeClass("disabled");
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
      url: "routes/loadCalendar",
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

  $(document).on("click", ".boxDay", function () {
    loadingResponse.show();
    calendarSelector.hide();
    scheduleSelector.show();

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
        calendarNavigation.hide();
        calendarBackDiv.show();
        loadingResponse.hide();
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        loadingResponse.hide();
      },
    });
  });

  $(document).on("click", ".boxSchedule", function () {
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
    console.log("s");
    event.preventDefault();
    scheduleFormMessage.slideUp();

    let date = picked.val();
    let schedule = picked.data("schedule");
    let count = picked.data("count");

    let dniRUC = $("#dniRUC").val();
    let client = $("#client").val();
    let email = $("#email").val();
    let phone = $("#phone").val();
    let machine = machineId.val();
    let invoice = $("#invoice")[0].files[0];

    if (
      !validateDniRuc(dniRUC) ||
      !validateClient(client) ||
      !validateEmail(email) ||
      !validatePhone(phone) ||
      !validateMachineId(machine) ||
      !validateInvoice(invoice)
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
    formData.append("invoice", invoice);

    $.ajax({
      url: "routes/registerSchedule",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        let jsonData = JSON.parse(response);
        if (jsonData.success) {
          scheduleCalendar.html(jsonData.success);
        } else {
          message(scheduleFormMessage, jsonData.error);
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

  function validateClient(client) {
    if (client.trim() === "") {
      message(scheduleFormMessage, "El campo del nombre no puede estar vacío");
      return false;
    }
    return true;
  }
  function validateEmail(email) {
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      message(scheduleFormMessage, "Ingrese un correo electrónico válido");
      return false;
    }
    return true;
  }
  function validatePhone(phone) {
    if (phone === "") {
      message(scheduleFormMessage, "Ingrese un número de teléfono");
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
  function validateInvoice(invoice) {
    if (!invoice) {
      message(scheduleFormMessage, "Seleccione un archivo");
      return false;
    }
    const maxSizeInBytes = 10 * 1024 * 1024; // 10 MB
    if (invoice.size > maxSizeInBytes) {
      message(scheduleFormMessage, "El tamaño del archivo es demasiado grande");
      return false;
    }

    let fileType = invoice.type;
    if (fileType !== "application/pdf" && !fileType.startsWith("image/")) {
      message(scheduleFormMessage, "El archivo debe ser PDF o una imagen");
      return false;
    }
    return true;
  }
  function validateDocument(document) {
    if (
      !(document.length === 8 || document.length === 11) ||
      !/^\d+$/.test(document)
    ) {
      message(searchOrderMessage, "Ingrese un documento válido");
      return false;
    }
    return true;
  }
  function validateorderNumber(orderNumber) {
    if (orderNumber.trim() === "" || !/^\d+$/.test(orderNumber)) {
      message(searchOrderMessage, "Ingrese un número de orden válido");
      return false;
    }
    return true;
  }
  let tme_pday = $(".tme-top");

  if (tme_pday) {
    tme_pday.each(function () {
      let dat_pday = $(this).data("stt");
      let dat_prc = dat_pday * 5;
      if (dat_prc > 100) dat_prc = 100;
      $(this).css("left", dat_prc + "%");
    });
  }
});
