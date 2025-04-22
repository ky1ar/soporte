$(document).ready(function () {
    $("#formConsulta").on("submit", function (e) {
      e.preventDefault();
    
      // Realiza la petición al archivo JSON sin validar el documento
      fetch("assets/js/data.json")
        .then((res) => res.json())
        .then((data) => {
          // Verifica si los datos recibidos son válidos
          if (!data.success || !Array.isArray(data.data)) {
            return; // Solo regresa si los datos son inválidos
          }
  
          // Obtiene la lista de pedidos
          const orders = data.data;
          const container = $("#listOrdersShipping");
          container.html('<h1 class="title">Mis Pedidos</h1>');
    
          // Accede al template del pedido
          const orderTemplate = document.querySelector("#order-template");
          if (!orderTemplate) {
            console.error("Template no encontrado");
            return;
          }
    
          // Recorre la lista de pedidos y muestra cada uno en el contenedor
          orders.forEach((order) => {
            const orderElement = orderTemplate.content.cloneNode(true);
            orderElement.querySelector(".order-number").textContent = order.order_number;
            orderElement.querySelector(".tracking-codes").textContent = `${order.code1} / ${order.code2}`;
            orderElement.querySelector(".agency-image").src = order.agency_image;
            orderElement.querySelector(".agency-image").alt = order.agency;
            orderElement.querySelector(".fecha").textContent = new Date(order.register_at).toLocaleDateString('es-PE', {
              year: 'numeric',
              month: 'long',
              day: 'numeric'
            });
            orderElement.querySelector(".status").textContent = order.status;
            orderElement.querySelector(".documento").textContent = "—"; // Cambié esto para no depender del documento
            container.append(orderElement);
          });
  
          // Muestra el contenedor con los pedidos
          container.fadeIn().css("display", "flex");
        })
        .catch((err) => {
          console.error(err);
        });
    });
  });
  