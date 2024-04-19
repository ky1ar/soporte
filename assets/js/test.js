var totalRows = parseInt(
  document.getElementById("totalRows").getAttribute("data-total")
);
var totalPages = Math.ceil(totalRows / 20);
var currentPage = 1; // Página actual, inicialmente la primera

function showPage(pageNumber) {
  currentPage = pageNumber;
  var rows = document.querySelectorAll(".rpt-tbl tr:not(.row-hdr)");
  var startIndex = (pageNumber - 1) * 20;
  var endIndex = startIndex + 20;

  rows.forEach(function (row, index) {
    if (index >= startIndex && index < endIndex) {
      row.style.display = "table-row";
    } else {
      row.style.display = "none";
    }
  });

  updatePaginationButtons(pageNumber);
}

function updatePaginationButtons(currentPage) {
  var pagination = document.getElementById("pagination");
  pagination.innerHTML = "";
  var startPage = currentPage > 2 ? currentPage - 1 : 1;
  var endPage = Math.min(startPage + 3, totalPages);

  if (currentPage > 5) {
    // Si estamos más allá del quinto elemento, agregamos el botón de elipsis
    var ellipsisButton = document.createElement("button");
    ellipsisButton.textContent = "...";
    ellipsisButton.addEventListener("click", function () {
      showPage(currentPage - 4); // Mostrar la página anterior al quinto elemento
    });
    pagination.appendChild(ellipsisButton);
  }

  for (var i = startPage; i <= endPage; i++) {
    var button = document.createElement("button");
    button.textContent = i;
    button.addEventListener("click", function () {
      showPage(parseInt(this.textContent));
    });

    if (i === currentPage) {
      button.classList.add("active");
    }
    pagination.appendChild(button);
  }

  if (currentPage < totalPages) {
    // Si el último botón actual no es la última página, agregar un botón adicional para la última página
    if (endPage < totalPages) {
      var lastPageButton = document.createElement("button");
      lastPageButton.textContent = totalPages;
      lastPageButton.addEventListener("click", function () {
        showPage(totalPages);
      });
      pagination.insertBefore(lastPageButton, pagination.lastChild.nextSibling); // Aquí se ajusta la inserción
    }
  }

  if (currentPage < totalPages && currentPage <= totalPages - 5) {
    // Si hay más de cinco páginas restantes después de la página actual, agregamos un botón de siguiente
    var nextButton = document.createElement("button");
    var img = document.createElement("img");
    img.src = "../assets/img/right.png";
    img.alt = "Siguiente";
    nextButton.appendChild(img);
    nextButton.addEventListener("click", function () {
      showPage(currentPage + 1);
    });
    pagination.appendChild(nextButton);
  }
}

showPage(1);
