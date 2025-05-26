<?php 
$currentPage = "Seguimiento de Orden"; 
require_once 'includes/app/db.php';
require_once 'includes/app/globals.php'; 
require_once 'includes/common/header.php';
?>

<body>
<?php 
require_once 'includes/bar/topBar.php';
require_once 'includes/bar/navigationBar.php';
?>

<section id="ky1-ord">
  <div class="ky1-wrp" id="order-content">
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const data = JSON.parse(localStorage.getItem("orderData"));
        if (!data) {
          window.location.href = "index.php";
          return;
        }

        const sttImg = ['one', 'two', 'thr', 'for', 'fiv', 'six', 'sev', 'eig', 'nin'];
        const currentStatusIndex = data.status_id - 1;
        const history = data.history || [];

        const machineSlug = data.machine
          .toLowerCase()
          .normalize("NFD")
          .replace(/[\u0300-\u036f]/g, "")  // Remove accents
          .replace(/[^a-z0-9]/g, '-')        // Replace spaces and symbols
          .replace(/-+/g, '-')               // Collapse dashes
          .replace(/^-|-$/g, '');            // Trim dashes

        const timelineHTML = history.map((step, i) => `
          <li class="tml-itm tml-act">
            <i class="tmt-lne"></i>
            <b class="tmt-dot">${i + 1}</b>
            <img class="tml-img" src="assets/img/${sttImg[i] || 'one'}.svg" alt="">
            <span>${step.status_name}</span>
          </li>
        `).join('');

        const remainingHTML = Array.from({ length: 9 - history.length }, (_, i) => {
          const index = history.length + i;
          return `
            <li class="tml-itm">
              <i class="tmt-lne"></i>
              <b class="tmt-dot">${index + 1}</b>
              <img class="tml-img" src="assets/img/${sttImg[index] || 'one'}.svg" alt="">
              <span>Estado ${index + 1}</span>
            </li>
          `;
        }).join('');

        const statusLogHTML = history.map((step, i) => {
          const isLast = i === history.length - 1;
          let icon = '<img src="assets/img/chk.svg" alt="">Completado';
          if (isLast) {
            icon = data.status_id === 9
              ? '<img src="assets/img/crr.svg" alt="">Finalizado'
              : '<img src="assets/img/crr.svg" alt="">En Proceso';
          }

          return `
            <li class="${isLast ? 'smy-crr' : 'smy-act'}">
              <i>${i + 1}</i>
              <div class="hst-cnt">
                <div class="hst-ttl">
                  <h3>${step.status_name}</h3>
                  <h4><img src="assets/img/cal.svg" alt="">${step.register_at}</h4>
                </div>
              </div>
              <div class="hst-dte">${icon}</div>
            </li>
          `;
        }).join('');

        const statusPendingHTML = Array.from({ length: 9 - history.length }, (_, i) => {
          const index = history.length + i;
          return `
            <li>
              <i>${index + 1}</i>
              <div class="hst-cnt">
                <div class="hst-ttl">
                  <h3>Estado ${index + 1}</h3>
                </div>
              </div>
            </li>
          `;
        }).join('');

        document.getElementById("order-content").innerHTML = `
          <div class="ord-tml">
            <ul class="tml-lst">
              ${timelineHTML}
              ${remainingHTML}
            </ul>
          </div>

          <div class="ord-smy">
            <div class="smy-lft">
              <div class="itm-hdr">
                <div class="itm-lft">
                  <h2>Orden <b>00${data.order_number}</b></h2>
                  <h3><img src="assets/img/tec.svg" alt="">${data.technician_name}</h3>
                </div>
                <span>${data.passed_days}</span>
              </div>

              <div class="itm-crd">
                <div class="imt-dat">
                  <h3>${data.machine}</h3>
                  <h2>${data.client_name}</h2>
                  <div class="itm-lnk"><p>${data.client_email ?? ''}</p></div>
                  <div class="itm-otr">
                    <h5>${data.method_name}</h5>
                    <h5>${data.origin_name}</h5>
                  </div>
                </div>
                <img class="itm-img" src="assets/mac/${machineSlug}.webp" alt="">
              </div>

              <span class="smy-stt">${data.status_text}</span>
              <div class="smy-tme">
                <h2>Total de días desde el ingreso</h2>
                <div class="tme-cnt">
                  <div id="tme-pdy" class="tme-top" data-stt="${data.passed_days}">
                    <b>${data.passed_days}</b>
                  </div>
                  <div class="tme-bot"></div>
                  <b>0</b>
                  <b>20</b>
                </div>
              </div>
            </div>

            <div class="smy-rgt">
              <ul>
                ${statusLogHTML}
                ${statusPendingHTML}
              </ul>
            </div>
          </div>
        `;
      });
    </script>
  </div>
</section>

<?php require_once 'includes/common/footer.php'; ?>
</body>
</html>
