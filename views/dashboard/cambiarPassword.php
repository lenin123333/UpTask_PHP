<?php include_once __DIR__ . '/header.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
    <a href="/perfil" class="enlace">Volver al perfil</a>


    <form action="/cambiar-password" class="formulario" method="POST">
        <div class="campo">
            <label for="password_actual">Password Actual</label>
            <input type="password"  name="password_actual" 
            placeholder="Tu Passwrod Actual">
        </div>

        <div class="campo">
            <label for="password_nueva">Password nueva</label>
            <input type="password" name="password_nueva" 
            placeholder="Tu Password nuevo">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>

</div>
<?php include_once __DIR__ . '/futter.php'; ?>