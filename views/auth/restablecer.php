<div class="contenedor restablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descipcion-pagina">Coloca tu nuevo Password </p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?> 
        <?php if ($mostrar) {?> 
        <form  class="formulario" method="post">

            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Tu password">
            </div>
            

            <input type="submit" class="boton" value="Guardar">
        </form>
        <?php }?> 
        <div class="acciones">
            <a href="/crear">¿Aun no tienes una cuenta? Obtener una</a>
            <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
        </div>
    </div>
</div>