<aside class="sidebar">
    <div class="container-sidebar">
        <h2><a href="/dashboard">SINCO</a></h2>
        
        <div class="close-menu">
            <img id="close-menu" src="build/img/close.svg" alt="imagen close-menu">
        </div>
    </div>

    <nav class="sidebar-nav">
        <a class="<?php echo ($title === 'Proyectos' ? 'active' : '' ) ?>" href="/dashboard">Inicio</a>
        <a class="<?php echo ($title === 'Nuevo Proyecto' ? 'active' : '' ) ?>" href="/new-project">Nuevo Proyecto</a>
        <a class="<?php echo ($title === 'Perfil' ? 'active' : '' ) ?>" href="/profile">Perfil</a>
    </nav>

    <div class="logout-mobile">
        <a class="logout" href="/logout">Cerrar Sesión</a>
    </div>
    
</aside>