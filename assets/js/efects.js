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

// let slideIndex = 0;

// function showSlide(index) {
//   const carousel = document.querySelector(".carousel");
//   const carouselItems = document.querySelectorAll(".carousel-item");
//   const slideWidth = carouselItems[0].offsetWidth;

//   if (index < 0) {
//     index = carouselItems.length - 1;
//   } else if (index >= carouselItems.length) {
//     index = 0;
//   }

//   carousel.style.transform = `translateX(-${slideWidth * index}px)`;
//   slideIndex = index;
// }

// function nextSlide() {
//   showSlide(slideIndex + 1);
// }

// function prevSlide() {
//   showSlide(slideIndex - 1);
// }

// // Mostrar el primer slide al cargar la página
// showSlide(slideIndex);

// // Obtener todos los elementos con la clase "truncate"
// const paragraphs = document.getElementsByClassName("truncate");

// // Iterar sobre la colección de elementos y llamar a la función para truncar el texto
// for (let i = 0; i < paragraphs.length; i++) {
//   truncateText(paragraphs[i], 100);
// }

// // Función para truncar el texto después de un cierto número de caracteres
// function truncateText(element, maxLength) {
//   let text = element.textContent;
//   if (text.length > maxLength) {
//     text = text.substring(0, maxLength); // Obtener los primeros maxLength caracteres
//     text = text.substring(0, Math.min(text.length, text.lastIndexOf(" "))); // Asegurarse de que el texto termina en una palabra completa
//     text += "..."; // Agregar puntos suspensivos
//   }
//   element.textContent = text; // Establecer el texto truncado de nuevo en el elemento
// }
