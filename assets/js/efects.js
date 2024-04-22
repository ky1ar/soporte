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
      if (screenWidth < 1650) {
          slider.classList.replace('slider-item-show4', 'slider-item-show3');
      }
      if (screenWidth < 1350) {
        slider.classList.replace('slider-item-show3', 'slider-item-show2');
      }else {
          slider.classList.replace('slider-item-show3', 'slider-item-show4');
      }
  });
}
window.onload = adjustSlider;
window.addEventListener('resize', adjustSlider);
