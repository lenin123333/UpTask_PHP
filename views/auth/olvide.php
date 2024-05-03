<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descipcion-pagina">Recupera tu contraseña de UpTask </p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?> 
        <form action="/olvide" class="formulario" method="post">

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu E-mail">
            </div>
            

            <input type="submit" class="boton" value="Enviar Instrucciones">
        </form>
        <div class="acciones">
            <a href="/crear">¿Aun no tienes una cuenta? Obtener una</a>
            <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
        </div>
    </div>
</div>