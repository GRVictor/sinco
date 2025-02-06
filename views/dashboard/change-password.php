<?php include_once __DIR__ . '/header-dashboard.php' ?>

<div class="container-sm">
    
    <a class="back" href="/profile">&larr; Volver</a>
    
    <?php include_once __DIR__ . '/../templates/alerts.php'; ?>

    <form class="form" method="POST" action="/change-password">

        <div class="field">
            <label for="password">Contraseña Actual</label>
            <input type="password" name="currentPassword" placeholder="Tu contraseña actual">
        </div>

        <div class="field">
            <label for="newPassword">Nueva Contraseña</label>
            <input type="password" name="newPassword" placeholder="Tu nueva contraseña">
        </div>

        <div class="field">
            <label for="confirmPassword">Confirmar Contraseña</label>
            <input type="password" name="confirmPassword" placeholder="Confirma tu nueva contraseña">
        </div>

        <input type="submit" value="Guardar">

    </form>

</div>

<?php include_once __DIR__ . '/footer-dashboard.php' ?>