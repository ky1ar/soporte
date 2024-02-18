<?php
require_once 'db.php';

if(isset($_GET['number'])):
    $o_number = $_GET['number'];

    $sql = "SELECT o.id, o.number as orders, o.dates, DATEDIFF(NOW(), o.dates) as pday, c.name, o.comments, c.email, c.document, c.phone, w.id as wid, w.name as wnm, m.model, b.name as bnm, m.slug, t.name as tnm, r.name as onm, o.state, s.name as snm FROM Orders o INNER JOIN Machine m ON o.machine = m.id INNER JOIN Users c ON o.client = c.id INNER JOIN Brand b ON m.brand = b.id INNER JOIN Users w ON o.worker = w.id INNER JOIN Type t ON o.type = t.id INNER JOIN Status s ON o.state = s.id INNER JOIN Origin r ON o.origin = r.id WHERE o.number = '$o_number'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) { 
        $order = $result->fetch_assoc();
    }
    $stt_img = ['one', 'two', 'thr', 'for', 'fiv', 'six', 'sev', 'eig', 'nin']; 

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/fav.png">
    <title>Krear 3D - Soporte Técnico</title>
    <?php require_once 'header_back.php'; ?>
</head>
<body>
    <div id="ky1-bar">
        <div class="ky1-wrp bar-enl"> 
            <a href="https://api.whatsapp.com/send?phone=51934760404" target="_blank" rel="nofollow"><img src="assets/img/wsp.svg" width="16" height="16" alt="ico">934 760 404</a>
            <a href="https://api.whatsapp.com/send?phone=51982001288" target="_blank" rel="nofollow"><img src="assets/img/wsp.svg" width="16" height="16" alt="ico">982 001 288</a>
            <a href="mailto:ventas2@krear3d.com" target="_blank" rel="nofollow"><img src="assets/img/eml.svg" width="16" height="16" alt="ico">ventas2@krear3d.com</a>
            <a href="https://bit.ly/2ZzWUeK" target="_blank" rel="nofollow"><img src="assets/img/map.svg" width="16" height="16" alt="ico">Calle Javier Fernández 262 Miraflores - Lima</a>
        </div>
    </div>

    <header id="ky1-hdr">
        <div class="ky1-wrp">
            <a href="/"><img class="hdr-lgo" width="150" height="42" src="assets/img/logod.webp" alt="Logo Krear 3D"></a>
            <div class="hdr-lft">
                <a href="/">Volver</a>
            </div>
        </div>
    </header>
    <section id="ky1-ord">
        <div class="ky1-wrp">
            <div class="ord-tml">
                <ul class="tml-lst">
                    <?php
                    $sql = "SELECT s.id, s.name FROM Orders_Status os INNER JOIN Status s ON os.stat = s.id WHERE os.orders = $order[id];";
                    $result = $conn->query($sql);
                    $n = 1;
                    $t = $result->num_rows;
                    while ($row = $result->fetch_assoc()):?>
                        <li class="tml-itm tml-act">
                            <i class="tmt-lne"></i>
                            <b class="tmt-dot"><?php echo $n ?></b>
                            <img class="tml-img" src="assets/img/<?php echo $stt_img[$row['id']-1] ?>.svg" alt="">
                            <span><?php echo $row['name'] ?></span>
                        </li>
                    <?php $n++;
                    endwhile; 

                    $sql = "SELECT * FROM Status WHERE id > $t";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()):?>
                        <li class="tml-itm">
                            <i class="tmt-lne"></i>
                            <b class="tmt-dot"><?php echo $n ?></b>
                            <img class="tml-img" src="assets/img/<?php echo $stt_img[$row['id']-1] ?>.svg" alt="">
                            <span><?php echo $row['name'] ?></span>
                        </li>
                    <?php $n++;
                    endwhile;?>
                </ul>
            </div>
            <div class="ord-smy">
                <div class="smy-lft">
                    <div class="itm-hdr">
                        <div class="itm-lft">
                            <h2>Orden <b>00</b><?php echo $order['orders'] ?></h2>
                            <h3><img src="assets/img/tec.svg" alt=""><?php echo $order['wnm'] ?></h3>
                        </div>
                        <span><?php echo $order['pday'] ?> días</span>
                    </div>
                    <div class="itm-crd">
                        <div class="imt-dat">
                            <h3><?php echo $order['bnm'] . ' ' . $order['model']?></h3>
                            <h2><?php echo $order['name'] ?></h2>
                            <div class="itm-lnk">
                                <p><?php echo $order['email'] ?></p>
                            </div>
                            <div class="itm-otr">
                                <h5><?php echo $order['tnm'] ?></h5>
                                <h5><?php echo $order['onm'] ?></h5>
                            </div>
                        </div>
                        <img class="itm-img" src="assets/mac/<?php echo $order['slug'] ?>.webp" alt="">
                    </div>
                    <span class="smy-stt">Estado actual de la orden: <b><?php echo $order['snm'] ?></b></span>
                    <div class="smy-tme">
                        <h2>Total de días desde el ingreso</h2>
                        <div class="tme-cnt">
                            <div id="tme-pdy" class="tme-top" data-stt="<?php echo $order['pday'] ?>"><b><?php echo $order['pday'] ?> días</b></div>
                            <div class="tme-bot"></div>
                            <b>0</b>
                            <b>20</b>
                        </div>
                    </div>
                </div>

                <div class="smy-rgt">
                    <ul>
                        <?php
                        $sql = "SELECT s.name as snm, os.notes, c.name as cnm, os.dates FROM Orders_Status os INNER JOIN Status s ON os.stat = s.id INNER JOIN Users c ON os.changer = c.id  WHERE os.orders = $order[id];";
                        $result = $conn->query($sql);
                        $n = 1;
                        $t = $result->num_rows;
                        setlocale(LC_TIME, 'es_ES');
                        while ($row = $result->fetch_assoc()):
                            $date = strtotime($row['dates']);
                            $date = strftime("%e de %B de %Y", $date);
                            ?>
                            <li class="<?php echo $n == $t ? 'smy-crr':'smy-act' ?>">
                                <i><?php echo $n ?></i>
                                <div class="hst-cnt">
                                    <div class="hst-ttl">
                                        <h3><?php echo $row['snm'] ?></h3>
                                        <h4><img src="assets/img/cal.svg" alt=""><?php echo $date ?></h4>
                                    </div>
                                </div>
                                <div class="hst-dte">
                                    <?php 
                                    if ($n == 9 ) {
                                        echo '<img src="assets/img/crr.svg" alt="">Finalizado';
                                    } elseif ($n == $t) {
                                        echo '<img src="assets/img/crr.svg" alt="">En Proceso';
                                    } else {
                                        echo '<img src="assets/img/chk.svg" alt="">Completado';
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php $n++;
                        endwhile;

                        $sql = "SELECT * FROM Status WHERE id > $t";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()):?>
                            <li>
                                <i><?php echo $n ?></i>
                                <div class="hst-cnt">
                                    <div class="hst-ttl">
                                        <h3><?php echo $row['name'] ?></h3>
                                    </div>
                                </div>
                            </li>
                        <?php $n++;
                        endwhile;
                        ?>
                    </ul>
                </div>
            </div>
            
        </div>
    </section>

    <footer>
        <div class="ftr-top">
            <div class="ky1-wrp">
                <div class="ftr-itm">
                    <img class="ftr-lgo" width="152" height="38" src="assets/img/logo.webp" alt="Krear 3D">
                    <ul>
                        <li><p>Calle Javier Fernandez - Miraflores - Lima</p></li>
                        <li><p>Lu - Sa de 9:00 am a 6:00 pm</p></li>
                    </ul>
                </div>
    
                <div class="ftr-itm">
                    <b>ATENCION AL CLIENTE</b>
                    <ul>
                        <li><a href="mailto:atencionalcliente@krear3d.com" target="_blank" rel="nofollow noopener">atencionalcliente@krear3d.com</a></li>
                        <li><p>Lu - Vi de 9:00 am a 6:00 pm</p></li>
                        <li><a href="https://api.whatsapp.com/send?phone=51981104030" target="_blank" rel="nofollow noopener"><img src="assets/img/wsp.svg" alt="Whatsapp" width="12" height="12">&nbsp;981 104 030</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="ftr-bot">
            <div class="ky1-wrp">
                <ul>
                    <li><a href="/terminos/terminos-condiciones-y-garantia/">POLÍTICAS Y CONDICIONES</a></li>
                    <li><a href="/terminos/politicas-de-garantia-y-soporte-tecnico/">POLÍTICAS DE GARANTÍA</a></li>
                    <li><a href="/terminos/politicas-de-envios-lima-y-provincias/">POLÍTICAS DE ENVÍOS</a></li>
                </ul>
                
                <span class="ftr-cpy">Fabricaciones Digitales del Perú S.A. | RUC 20556316890<br>Krear 3D © 2023. Todos los derechos reservados.</span>
            </div>
        </div>
        <script>
            if (window.history.replaceState) {
    // Reemplazar la URL actual sin parámetros
    var cleanURL = window.location.origin + window.location.pathname;
    window.history.replaceState({}, document.title, cleanURL);
}
        </script>
    </footer>
</body>
</html>
<?php
else:
    header("Location: index.php");
    exit();
endif;

?>