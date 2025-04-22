$(document).ready(function () {
    $("#formConsulta").on("submit", function (e) {
      e.preventDefault();
      const documento = $("#documento").val().trim();
  
      fetch("assets/js/data.json")
        .then((res) => res.json())
        .then((data) => {
          if (!data.success || !Array.isArray(data.data)) {
            alert("Error en los datos recibidos");
            return;
          }
  
          const orders = data.data;
          const container = $("#listOrdersShipping");
          container.html('<h1 class="title">Mis Pedidos</h1>'); // Aseguramos que el contenedor está vacío
  
          // Accedemos al template
          const orderTemplate = document.getElementById("order-template");
  
          // Iteramos sobre los pedidos y los agregamos
          orders.forEach((order) => {
            // Clonamos el template
            const orderElement = orderTemplate.content.cloneNode(true);
  
            // Rellenamos los datos en el template
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
            orderElement.querySelector(".documento").textContent = documento;
  
            // Agregamos el pedido al contenedor
            container.append(orderElement);
          });
  
          container.fadeIn().css("display", "flex");
        })
        .catch((err) => {
          console.error(err);
          alert("Error al cargar los datos");
        });
    });
  });
  