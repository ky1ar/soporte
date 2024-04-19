var totalRows = parseInt(
  document.getElementById("totalRows").getAttribute("data-total")
);
var totalPages = Math.ceil(totalRows / 20);
var currentPage = 1;

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
  var startPage, endPage;

  if (totalPages <= 7) {
    startPage = 1;
    endPage = totalPages;
  } else {
    if (currentPage <= 4) {
      startPage = 1;
      endPage = 5;
    } else if (currentPage + 3 >= totalPages) {
      startPage = totalPages - 5;
      endPage = totalPages;
    } else {
      startPage = currentPage - 2;
      endPage = currentPage + 2;
    }
  }

  if (currentPage > 1) {
    var prevButton = document.createElement("button");
    var img = document.createElement("img");
    img.src = "../assets/img/left.png";
    img.alt = "Anterior";
    prevButton.appendChild(img);
    prevButton.addEventListener("click", function () {
      showPage(currentPage - 1);
    });
    pagination.appendChild(prevButton);
  }

  if (startPage > 1) {
    var firstPageButton = document.createElement("button");
    firstPageButton.textContent = 1;
    firstPageButton.addEventListener("click", function () {
      showPage(1);
    });
    pagination.appendChild(firstPageButton);

    if (startPage > 2) {
      var ellipsisButton = document.createElement("button");
      ellipsisButton.textContent = "...";
      ellipsisButton.disabled = true;
      pagination.appendChild(ellipsisButton);
    }
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

  if (endPage < totalPages) {
    var lastPageButton = document.createElement("button");
    lastPageButton.textContent = totalPages;
    lastPageButton.addEventListener("click", function () {
      showPage(totalPages);
    });
    pagination.appendChild(lastPageButton);
  }

  if (currentPage < totalPages) {
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