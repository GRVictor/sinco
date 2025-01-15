<?php

namespace Controllers;

use MVC\Router;

class LoginController {
    public static function login(Router $router) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
        }

        // View Render
        $router -> render('auth/login', [
            'title' => 'Iniciar Sesión'
        ]);

    }

    public static function logout() {
        echo "Logout";
    }

    public static function register(Router $router) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
        }

        // View Render
        $router -> render('auth/register', [
            'title' => 'Crea tu cuenta'
        ]);
    }

    public static function forgot(Router $router) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
        }

        // View Render
        $router -> render('auth/forgot', [
            'title' => 'Olvidé mi contraseña'
        ]);
    }

    public static function reset(Router $router) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
        }

        // View Render
        $router -> render('auth/reset', [
            'title' => 'Restablecer Contraseña'
        ]);
    }

    public static function message(Router $router) {
        
        // View Render
        $router -> render('auth/message', [
            'title' => 'Mensaje'
        ]);
    }

    public static function confirm(Router $router) {
        
        // View Render
        $router -> render('auth/confirm', [
            'title' => 'Confirmar Cuenta'
        ]);
    }

}