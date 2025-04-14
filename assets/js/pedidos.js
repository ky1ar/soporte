function mostrarFormSh(num) {
  const formularios = ["formShalom", "formOlva", "formMarvisur"];
  document
    .querySelectorAll("#viewPedidos .forms .formulario")
    .forEach((div) => div.classList.remove("active"));
  document
    .querySelectorAll("#viewPedidos .menu button")
    .forEach((btn) => btn.classList.remove("active"));
  document.getElementById(formularios[num - 1]).classList.add("active");
  document
    .querySelectorAll("#viewPedidos .menu button")
    [num - 1].classList.add("active");
}

$("#viewPedidos .shalom #rastreoForm").on("submit", function (e) {
  e.preventDefault();

  const $form = $(this);
  const $resultado = $("#viewPedidos .shalom #resultado");

  $.ajax({
    url: "routes/scrapShalom.php",
    method: "POST",
    data: $form.serialize(),
    dataType: "json",
    success: function (response) {
      if (response.success) {
        const data = response.data;
        $resultado.html(`
            <p><strong>Número de orden:</strong> ${data.numero_orden}</p>
            <p><strong>Código de orden:</strong> ${data.codigo_orden}</p>
            <p><strong>Remitente:</strong> ${data.remitente.nombre}</p>
            <p><strong>Destinatario:</strong> ${data.destinatario.nombre}</p>
            <p><strong>Dirección entrega:</strong> ${data.direccion_entrega}</p>
            <p><strong>Estado de entrega:</strong> ${
              data.entregado ? "Entregado" : "En tránsito"
            }</p>
            <p><strong>Origen:</strong> ${data.origen.departamento} - ${
          data.origen.distrito
        }</p>
            <p><strong>Destino:</strong> ${data.destino.departamento} - ${
          data.destino.distrito
        }</p>
            <p><strong>Contenido:</strong> ${data.contenido}</p>
            <p><strong>Monto:</strong> S/. ${data.monto}</p>
            <p><strong>Tiempo estimado:</strong> ${data.tiempo_llegada}</p>
          `);
      } else {
        $resultado.html(`<p style="color:red;">${response.message}</p>`);
      }
    },
    error: function () {
      $resultado.html(
        `<p style="color:red;">Error en la consulta. Inténtalo de nuevo.</p>`
      );
    },
  });
});
