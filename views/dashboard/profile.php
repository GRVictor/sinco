<?php include_once __DIR__ . '/header-dashboard.php' ?>

<div class="container-sm">
    <?php include_once __DIR__ . '/../templates/alerts.php'; ?>

    <form class="form" method="POST" action="/profile">
        <div class="field">
            <label for="name">Nombre</label>
            <input type="text" name="name" placeholder="Tu nombre" value="<?php echo $user -> name ?>">
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Tu email" value="<?php echo $user -> email ?>">
        </div>

        <input type="submit" value="Guardar">

        <small><a href="/change-password">Cambiar Contraseña</a></small>

    </form>

</div>

<?php include_once __DIR__ . '/footer-dashboard.php' ?>