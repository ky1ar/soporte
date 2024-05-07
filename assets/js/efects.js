// document.addEventListener("DOMContentLoaded", function () {
//   const buttons = document.querySelectorAll(".collapsible");

//   buttons.forEach(function (button) {
//       button.addEventListener("click", function () {
//           const content = this.parentElement.nextElementSibling;
//           content.classList.toggle("active"); // toggle() agrega o quita la clase "active"
//       });
//   });
// });

document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".collapsible");

  buttons.forEach(function (button) {
      button.addEventListener("click", function () {
          const content = this.nextElementSibling;
          content.classList.toggle("active");
          if (content.classList.contains("active")) {
              content.style.height = content.scrollHeight + "px";
          } else {
              content.style.height = "0";
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
