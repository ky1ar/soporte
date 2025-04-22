$(document).ready(function () {
    let isProcessing = false;

    $("#formConsulta").on("submit", function (e) {
        e.preventDefault();
        if (isProcessing) return;

        isProcessing = true;
        const documento = $("#documento").val().trim();

        if (documento !== '70986545') {
            $("#error-message").html('<p>Documento no encontrado</p>').fadeIn().delay(1500).fadeOut(() => isProcessing = false);
            return;
        }

        $("#error-message").fadeOut(() => isProcessing = false);

        fetch("assets/js/data.json")
            .then(res => res.json())
            .then(data => {
                if (!data.success || !Array.isArray(data.data)) return;

                const orders = data.data;
                const container = $("#listOrdersShipping");
                container.html('<h1 class="title">Mis Pedidos</h1>');  // Limpiar contenido anterior

                // Llenar con las órdenes
                orders.forEach(order => {
                    // Clonamos la plantilla de la orden
                    const orderElement = $("#orderTemplate").clone().removeAttr("id").show();

                    // Actualizamos los valores
                    orderElement.find(".order-number").text(order.order_number);
                    orderElement.find(".tracking").text(`${order.code1} / ${order.code2}`);
                    orderElement.find(".agency-image").attr("src", order.agency_image).attr("alt", order.agency);
                    orderElement.find(".fecha").text(new Date(order.register_at).toLocaleDateString('es-PE', { year: 'numeric', month: 'long', day: 'numeric' }));
                    orderElement.find(".status").text(order.status);
                    orderElement.find(".documento").text(documento);
                    orderElement.find(".op").data("code1", order.code1).data("code2", order.code2).data("agency", order.agency_id);
                    orderElement.find("a").attr("href", `https://wa.me/51908944969?text=Hola,%20quisiera%20hacer%20una%20consulta%20sobre%20mi%20compra%20con%20número%20de%20orden%3A%20${order.order_number}`);

                    // Añadimos la orden al contenedor
                    container.append(orderElement);
                });

                container.fadeIn().css("display", "flex");
            })
            .catch(err => {
                console.error(err);
            });
    });
});
