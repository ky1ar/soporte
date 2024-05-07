document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".collapsible");

  buttons.forEach(function (button) {
    button.addEventListener("click", function () {
      const content = this.parentElement.nextElementSibling;
      const isActive = this.classList.contains("active");

      // Cerrar todos los contenidos que no están activos
      const allContents = document.querySelectorAll(".content:not(.active)");
      allContents.forEach(function (content) {
        content.style.height = "0";
        content.style.transition = "height 0.5s";
        setTimeout(function () {
          content.style.display = "none";
        }, 500);
      });

      // Remover la clase "active" de todos los botones que no están activos
      const allButtons = document.querySelectorAll(".collapsible:not(.active)");
      allButtons.forEach(function (button) {
        button.classList.remove("active");
      });

      // Si el botón no está activo, abrir el contenido
      if (!isActive) {
        content.style.height = content.offsetHeight + "px";
        content.style.transition = "height 0.5s";
        setTimeout(function () {
          content.style.display = "block";
        }, 500);
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
      // }else if (screenWidth < 1350) {
      //   slider.classList.replace('slider-item-show3', 'slider-item-show2');
      }else {
          slider.classList.replace('slider-item-show3', 'slider-item-show4');
      }
  });
}
window.onload = adjustSlider;
window.addEventListener('resize', adjustSlider);
