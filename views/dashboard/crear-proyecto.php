<?php include_once __DIR__ . '/header.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <form action="/crear-proyecto" class="formulario" method="post">
        <?php include_once __DIR__ . '/formulario.php'; ?>
        <input type="submit" value="Crear Proyecto">
    </form>
</div>


<?php include_once __DIR__ . '/futter.php'; ?>