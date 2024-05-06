<?php
$pages = [
    ['Consultas', ''],
    ['Capacitaciones', 'capacitaciones'],
    // ['Conocimiento', 'conocimiento'],
    // ['Cursos', 'cursos']
];

?>
<header id="navigationBar">
    <div class="wrapper">
        <a href="/"><img class="logo" width="150" height="42" src="assets/img/Logo-soporte.svg" alt="Logo Krear 3D"></a>
        <ul>
            <?php foreach ($pages as $page): ?>
                <li>
                    <a href="/<?php echo $page[1] ?>" class="<?php echo $currentPage == $page[0] ? 'active':''?>">
                        <?php echo $page[0] ?>
                    </a>
                </li>
            <?php endforeach;?>
        </ul>
        <a class="link" href="https://tiendakrear3d.com/">
            Tienda
            <img class="hdr-wsp" width="16" height="16" src="assets/img/tnd.svg" alt="ico">
        </a>
    </div>
</header>
