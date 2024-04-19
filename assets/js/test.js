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
  var startPage = currentPage > 2 ? currentPage - 1 : 1;
  var endPage = Math.min(startPage + 3, totalPages);

  if (endPage === totalPages) {
    startPage = Math.max(totalPages - 4, 1);
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

  if (currentPage <= totalPages - 4) {
    var ellipsisButton = document.createElement("button");
    ellipsisButton.textContent = "...";
    pagination.appendChild(ellipsisButton);
  }

  if (currentPage < totalPages) {
    if (endPage < totalPages) {
      var lastPageButton = document.createElement("button");
      lastPageButton.textContent = totalPages;
      lastPageButton.addEventListener("click", function () {
        showPage(totalPages);
      });
      pagination.appendChild(lastPageButton);
    }
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


var dataTable = document.getElementById("data-table");
var paginationSection = document.getElementById("pagination");
paginationSection.addEventListener("click", function(event) {
    if (event.target.tagName === "BUTTON") {
        dataTable.scrollIntoView({ behavior: "smooth", block: "start" });
    }
});

var paginationSections = document.getElementsByClassName("pagination");
for (var i = 0; i < paginationSections.length; i++) {
    var paginationSection = paginationSections[i];
    paginationSection.addEventListener("click", function(event) {
        if (event.target.tagName === "BUTTON") {
            document.body.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    });
}
