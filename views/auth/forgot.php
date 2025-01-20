<div class="container forgot">

    <?php include_once __DIR__ . '/../templates/logo-header.php'; ?>

    <div class="container-sm">
        <p class="page-description">Olvidé mi contraseña</p>

        <?php include_once __DIR__ . '/../templates/alerts.php'; ?>

        <form action="/forgot" class="form" method="POST">

            <div class="field">
                <label for="email">Ingresa tu correo</label>
                <input type="email" name="email" id="email" placeholder="ejemplo@ejemplo.com">
                <small>Te enviarémos un correo para que puedas reestaurar tu contraseña</small>
            </div>

            <input type="submit" class="button button-primary" value="Enviar">
        </form>


        <div class="actions">
            <a href="/" class="link">Iniciar Sesión</a>
            <a href="/register" class="link">¿No tienes cuenta? Regístrate</a>
        </div>

    </div> <!-- .container-sm -->
</div> <!-- .container -->