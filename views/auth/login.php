<div class="contenedor login">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descipcion-pagina">Crea tu cuenta en UpTask</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?> 
        <form action="/" class="formulario" method="post">

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu E-mail">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Tu Password">
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>
        <div class="acciones">
            <a href="/crear">¿Aun no tienes una cuenta? Obtener una</a>
            <a href="/olvide">¿Olvidaste tu Password?</a>
        </div>
    </div>
</div>