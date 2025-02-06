<?php 

namespace Controllers;

use Model\User;
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
        $alerts = [];
        
        $user = User::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user -> sync($_POST);
            $alerts = $user -> validateProfile();

            if(empty($alerts['error'])) {

                $userExist = User::where('email', $user -> email);

                if($userExist && $userExist -> id !== $user -> id) {
                    User::setAlert('error', 'Email ya registrado');
                    $alerts = User::getAlerts();
                } else {
                    $user -> save();
                    User::setAlert('success', 'Cambios guardados');
                    $alerts = User::getAlerts();
                    $_SESSION['name'] = $user -> name;
                }
            }

        }
        
        $router -> render('dashboard/profile', [
            'title' => 'Perfil',
            'user' => $user,
            'alerts' => $alerts
        ]);
    }

    public static function changePassword(Router $router) {
        session_start();
        isAuth();

        $alerts = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = User::find($_SESSION['id']);
            
            // Sync
            $user -> sync($_POST);
            $alerts = $user -> newPassword();

            if(empty($alerts['error'])) {
                $result = $user -> verifyPassword();

                if($result) {
                    // Save new password
                    $user -> password = $user -> newPassword;
                    // Delete temporary data
                    unset($user -> currentPassword);
                    unset($user -> confirmPassword);
                    unset($user -> newPassword);
                    // Hash password
                    $user -> hashPassword();
                    // Save
                    $result = $user -> save();

                    if($result) {
                        User::setAlert('success', 'Contraseña actualizada');
                        $alerts = User::getAlerts();
                    } else {
                        User::setAlert('error', 'Hubo un error al actualizar la contraseña');
                        $alerts = User::getAlerts();
                    }

                } else {
                    User::setAlert('error', 'Contraseña incorrecta');
                    $alerts = User::getAlerts();
                }

            }


        }

        $router->render('dashboard/change-password', [
            'title' => 'Cambiar contraseña',
            'alerts' => $alerts
        ]);
    }

}