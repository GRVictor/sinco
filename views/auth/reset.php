<div class="container reset">

    <?php include_once __DIR__ . '/../templates/logo-header.php'; ?>

    <div class="container-sm">
        <p class="page-description">Restablece tu Contraseña</p>
        <form action="/" class="form" method="POST">

            <div class="field">
                <label for="password">Nueva Contraseña</label>
                <input type="password" name="password" id="password" placeholder="********">
            </div>
            <div class="field">
                <label for="confirm">Confirmar Contraseña</label>
                <input type="password" name="confirm" id="confirm" placeholder="********">
            </div>

            <input type="submit" class="button button-primary" value="Restablecer">
        </form>

        <div class="actions">
            <a href="/" class="link">Iniciar Sesión</a>
            <a href="/register" class="link">¿No tienes cuenta? Regístrate</a>
        </div>

    </div> <!-- .container-sm -->
</div> <!-- .container -->