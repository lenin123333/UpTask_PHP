<?php include_once __DIR__ . '/header.php'; ?>

<?php if (count($proyectos) === 0) { ?>
    <p class="No-proyectos">No Hay Proyectos Aún</p>
    <a href="/crear-proyecto">Comienza creando uno</a>
<?php } else { ?>
    <ul class="listado-proyectos">
        <?php foreach ($proyectos as $proyecto) { ?>
            <li class="proyecto">
                <a href="/proyecto?id=<?php echo $proyecto->url ?>">
                    <?php echo $proyecto->proyecto; ?>
                </a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>
<?php include_once __DIR__ . '/futter.php'; ?>