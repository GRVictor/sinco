<?php

namespace Controllers;

use MVC\Router;
use Model\User;

class LoginController {
    public static function login(Router $router) {

        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lógica para manejar el inicio de sesión y llenar $alerts si hay errores
        }

        // View Render
        $router -> render('auth/login', [
            'title' => 'Iniciar Sesión',
            'alerts' => $alerts
        ]);

    }

    public static function logout() {
        echo "Logout";
    }

    public static function register(Router $router) {

        $alerts = [];
        $user = new User;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->sync($_POST);
            $alerts = $user->validateAccount();
        }

        // View Render
        $router -> render('auth/register', [
            'title' => 'Crea tu cuenta',
            'user' => $user,
            'alerts' => $alerts
        ]);
    }

    public static function forgot(Router $router) {

        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lógica para manejar el formulario de olvido de contraseña y llenar $alerts si hay errores
        }

        // View Render
        $router -> render('auth/forgot', [
            'title' => 'Olvidé mi contraseña',
            'alerts' => $alerts
        ]);
    }

    public static function reset(Router $router) {

        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lógica para manejar el formulario de restablecimiento de contraseña y llenar $alerts si hay errores
        }

        // View Render
        $router -> render('auth/reset', [
            'title' => 'Restablecer Contraseña',
            'alerts' => $alerts
        ]);
    }

    public static function message(Router $router) {
        
        $alerts = [];

        // View Render
        $router -> render('auth/message', [
            'title' => 'Mensaje',
            'alerts' => $alerts
        ]);
    }

    public static function confirm(Router $router) {
        
        $alerts = [];

        // View Render
        $router -> render('auth/confirm', [
            'title' => 'Confirmar Cuenta',
            'alerts' => $alerts
        ]);
    }

}