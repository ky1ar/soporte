document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".collapsible");

  buttons.forEach(function (button) {
    button.addEventListener("click", function () {
      const content = this.parentElement.nextElementSibling;
      const isActive = this.classList.contains("active");

      // Cerrar todos los contenidos
      const allContents = document.querySelectorAll(".content");
      allContents.forEach(function (content) {
        content.style.display = "none";
      });

      // Remover la clase "active" de todos los botones
      const allButtons = document.querySelectorAll(".collapsible");
      allButtons.forEach(function (button) {
        button.classList.remove("active");
      });

      // Si el botón no está activo, abrir el contenido
      if (!isActive) {
        content.style.display = "block";
        this.classList.add("active");
      }
    });
  });
});

function adjustSlider() {
  var screenWidth = window.innerWidth;
  var sliders = document.querySelectorAll('.swiffy-slider');
  sliders.forEach(function (slider) {
      if (screenWidth < 1000) { // Cambiado a 1000 en lugar de 1650
          slider.classList.remove('show4');
          slider.classList.add('show3');
      } else {
          slider.classList.remove('show3');
          slider.classList.add('show4');
      }
  });
}
window.onload = adjustSlider;
window.addEventListener('resize', adjustSlider);