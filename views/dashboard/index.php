<?php include_once __DIR__ . '/header-dashboard.php' ?>

    <?php if(count($projects) === 0 ) { ?>
        <p class="no-projects">No haz creado ningún proyecto, <a href="/new-project">crea tu primer proyecto.</a></p>
    <?php } else { ?>
        <ul class="project-list">
            <?php foreach($projects as $project) { ?>
                <li class="project">
                    <a href="/project?url=<?php echo $project->url; ?>">
                        <?php echo $project->project; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

<?php include_once __DIR__ . '/footer-dashboard.php' ?>a