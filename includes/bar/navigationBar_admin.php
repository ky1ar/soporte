<?php
$pages = [
    ['Equipos', 'grid'],
    ['Capacitaciones', 'training']
];

$super = [
    ['H. de Equipos', 'report'],
    ['H. de Capacitaciones', 'training_report'],
    ['Técnicos', '#'],
    ['Clientes', '#']
];

$links = $s_levels >= 3 ? array_merge($pages, $super) : $pages;

?>
<header id="navigationBar">
    <div class="wrapper">
        <a href="/"><img class="logo" width="150" height="42" src="assets/img/logo_v3.png" alt="Logo Krear 3D"></a>
        <ul>
            <?php foreach ($links as $page): ?>
                <li>
                    <a href="/<?php echo $page[1] ?>" class="<?php echo $currentPage == $page[0] ? 'active':''?>">
                        <?php echo $page[0] ?>
                    </a>
                </li>
            <?php endforeach;?>
        </ul>
        <a class="link" href="/">
            Ayuda
            <img class="hdr-wsp" width="16" height="16" src="assets/img/home.svg" alt="ico">
        </a>
    </div>
</header>
