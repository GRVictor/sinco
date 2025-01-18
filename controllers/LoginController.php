<?php

namespace Controllers;

use Classes\Email;
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
            
            if (!empty($alerts)) {
                $userExists = User::where('email', $user->email);

                if ($userExists) {
                    User::setAlert('error', 'El email ya está registrado');
                    $alerts = User::getAlerts();
                } else {
                    // Hash password and generate token
                    $user->hashPassword();

                    // Delete confirm from the user object
                    unset($user->confirm);

                    // Generate token
                    $user->generateToken();

                    // Send email
                    $email = new Email($user->name, $user->email, $user->token);
                    $email->sendEmail();

                    // Save user in the database
                    $result = $user->save();

                    if($result) {
                        header('Location: /message');
                    }
                }
            }

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

        
        $token = s($_GET['token']);
        $alerts = [];
        
        if (!$token) {
            header('Location: /');
        }

        // FInd user by token
        $user = User::where('token', $token);

        if (empty($user)) {
            // Token not valid
            User::setAlert('error', 'Token no válido');
        } else {
            // Confirm account
            $user->confirmed = 1;
            $user->token = "";
            unset($user->confirm);

            $result = $user->save();

            User::setAlert('success', 'Cuenta confirmada');
        }

        $alerts = User::getAlerts();

        // View Render
        $router -> render('auth/confirm', [
            'title' => 'Confirmar Cuenta',
            'alerts' => $alerts
        ]);
    }

}