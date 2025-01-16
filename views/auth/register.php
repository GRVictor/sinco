<div class="container register">

    <?php include_once __DIR__ . '/../templates/logo-header.php'; ?>

    <div class="container-sm">
        <p class="page-description">Crea tu cuenta</p>
        <form action="/register" class="form" method="POST">
            <div class="field">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" placeholder="Tu Nombre" value="<?php echo $user->name; ?>">
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu Email">
            </div>
            <div class="field">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="********">
            </div>
            <div class="field">
                <label for="confirm">Confirmar Contraseña</label>
                <input type="password" name="confirm" id="confirm" placeholder="********">
            </div>

            <input type="submit" class="button button-primary" value="Registrarme">
        </form>

        <div class="actions">
            <a href="/" class="link">Iniciar Sesión</a>
            <a href="/forgot" class="link">¿Olvidaste tu contraseña?</a>
        </div>

    </div> <!-- .container-sm -->
</div> <!-- .container -->