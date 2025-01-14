<div class="container home">

    <?php include_once __DIR__ . '/../templates/logo-header.php'; ?>

    <div class="container-sm">
        <p class="page-description">Iniciar Sesión</p>
        <form action="/" class="form" method="POST">
            <div class="field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="ejemplo@ejemplo.com">
            </div>
            <div class="field">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="********">
            </div>

            <input type="submit" class="button button-primary" value="Iniciar Sesión">
        </form>

        <div class="actions">
            <a href="/register" class="link">¿No tienes cuenta? Regístrate</a>
            <a href="/forgot" class="link">¿Olvidaste tu contraseña?</a>
        </div>

    </div> <!-- .container-sm -->
</div> <!-- .container -->