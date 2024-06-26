<div class="contenedor crear">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descipcion-pagina">Crear Cuenta</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form action="/crear" class="formulario" method="post">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Tu Nombre" value="<?php echo $usuario->nombre; ?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu E-mail" value="<?php echo $usuario->email; ?>">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Tu Password">
            </div>
            <div class="campo">
                <label for="password2">Password</label>
                <input type="password" name="password2" id="password2" placeholder="Repite tu Password">
            </div>

            <input type="submit" class="boton" value="Crear cuenta">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
            <a href="/olvide">¿Olvidaste tu Password?</a>
        </div>
    </div>
</div>