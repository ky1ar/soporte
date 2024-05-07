document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".collapsible");

  buttons.forEach(function (button) {
    button.addEventListener("click", function () {
      const content = this.parentElement.nextElementSibling;
      const isActive = this.classList.contains("active");

      // Si el botón está activo, cerrar el contenido
      if (isActive) {
        content.style.display = "none";
        this.classList.remove("active");
      } else {
        // Si el botón no está activo, abrir el contenido
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
      // }else if (screenWidth < 1350) {
      //   slider.classList.replace('slider-item-show3', 'slider-item-show2');
      }else {
          slider.classList.replace('slider-item-show3', 'slider-item-show4');
      }
  });
}
window.onload = adjustSlider;
window.addEventListener('resize', adjustSlider);
