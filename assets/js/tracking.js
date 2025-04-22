// Lista de Pedidos con fetch desde data.json
$(document).ready(function () {
    $("#formConsulta").on("submit", function (e) {
      e.preventDefault();
      const documento = $("#documento").val().trim();
  
      fetch("data.json")
        .then((res) => res.json())
        .then((data) => {
          if (!data.success || !Array.isArray(data.data)) {
            alert("Error en los datos recibidos");
            return;
          }
          const orders = data.data;
          const container = $("#listOrdersShipping");
          container.html('<h1 class="title">Mis Pedidos</h1>');
          orders.forEach((order) => {
            container.append(`
              <div class="order">
                <div class="head">
                  <p class="orderNum">Orden: <span>${order.order_number}</span></p>
                  <p class="track">Tracking: <span>${order.code1} / ${order.code2}</span></p>
                  <div class="agencia">
                    <img src="${order.agency_image}" alt="${order.agency}" class="a${order.agency_id}">
                  </div>
                </div>
                <div class="cont">
                  <div class="info">
                    <p class="fecha">${new Date(order.register_at).toLocaleDateString('es-PE', {
                      day: 'numeric',
                      month: 'long'
                    })}</p>
                    <p class="status">${order.status}</p>
                    <p class="name">Nombre: <span>-</span></p>
                    <p class="doc">Documento: <span>${documento}</span></p>
                  </div>
                  <div class="actions">
                    <button class="btn op" data-code1="${order.code1}" data-code2="${order.code2}" data-agency="${order.agency_id}">Rastrear</button>
                    <a class="btn" href="https://wa.me/51908944969?text=Hola,%20quisiera%20hacer%20una%20consulta%20sobre%20mi%20compra%20con%20número%20de%20orden%3A%20${order.order_number}" target="_blank">Obtener Ayuda</a>
                  </div>
                </div>
              </div>
            `);
          });
  
          container.fadeIn().css("display", "flex");
        })
        .catch((error) => {
          console.error("Error al obtener los datos:", error);
          alert("Hubo un error al obtener los datos");
        });
    });
  });
  