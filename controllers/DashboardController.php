<?php 

namespace Controllers;

use Model\Project;
use MVC\Router;

class DashboardController {

    public static function index(Router $router) {
        
        session_start();

        isAuth();

        $id = $_SESSION['id'];

        $projects = Project::belongsTo('ownerId', $id);
        
        $router -> render('dashboard/index', [
            'title' => 'Proyectos',
            'projects' => $projects
        ]);
    }

    public static function newProject(Router $router) {
        
        session_start();
        isAuth();
        $alerts = [];

        if($_SERVER ['REQUEST_METHOD'] === 'POST') {
            $project = new Project($_POST);

            // Validation
            $alerts = $project -> validateProject();

            if(empty($alerts['error'])) {
                // Generate URL
                $hash = bin2hex(random_bytes(16));
                $project -> url = $hash;
                // Save project owner
                $project -> ownerId = $_SESSION['id'];
                // Save project
                $project -> save();
                // Redirect
                header('Location: /project?url=' . $project -> url);
            }

        }
        
        $router -> render('dashboard/new-project', [
            'title' => 'Nuevo Proyecto',
            'alerts' => $alerts
        ]);
    }

    public static function project(Router $router) {

        session_start();
        isAuth();

        $token = $_GET['url'];

        if(!$token) {
            header('Location: /dashboard');
        }
        // Check if the project owner is the same as the user logged in
        $project = Project::where('url', $token);
        if($project->ownerId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $router -> render('dashboard/project', [
            'title' => $project -> project,
        ]);
    }

    public static function profile(Router $router) {
        
        session_start();

        isAuth();
        
        $router -> render('dashboard/profile', [
            'title' => 'Perfil'
        ]);
    }



}