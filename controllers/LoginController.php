<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\User;

class LoginController {
    public static function login(Router $router) {

        $alerts = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User($_POST);
            $alerts = $user->validateLogin();
            
            if(empty($alerts['error'])) {
                // If the user exists
                $user = User::where('email', $user->email);

                if(!$user) {
                    User::setAlert('error', 'El usuario no existe');
                } elseif (!$user->confirmed) {
                    User::setAlert('error', 'La cuenta no está confirmada');
                } else {
                    // The user exists and is confirmed
                    if (password_verify($_POST['password'], $user->password)) {
                        // Start session
                        session_start();

                        // Set session variables
                        $_SESSION['id'] = $user->id;
                        $_SESSION['name'] = $user->name;
                        $_SESSION['email'] = $user->email;
                        $_SESSION['login'] = true;

                        // Redirect
                        header('Location: /dashboard');
                    } else {
                        User::setAlert('error', 'La contraseña es incorrecta');
                    }
                }

            }   

        }


        $alerts = User::getAlerts();
        // View Render
        $router -> render('auth/login', [
            'title' => 'Iniciar Sesión',
            'alerts' => $alerts
        ]);

    }

    public static function logout() {
        session_start();
        $_SESSION = [];

        header('Location: /');

    }

    public static function register(Router $router) {

        $alerts = [];
        $user = new User;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->sync($_POST);
            $alerts = $user->validateAccount();
            
            if (empty($alerts['error'])) {
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
            $user = new User($_POST);
            $alerts = $user->validateEmail();

            if(empty($alerts['error'])) {
                $user = User::where('email', $user->email);

                if ($user && $user->confirmed) {
                    // User exists and is confirmed, we first generate a token
                    $user->generateToken();
                    unset($user->confirm);

                    // Save user in the database
                    $user->save();

                    // Send email
                    $email = new Email($user->email, $user->name, $user->token);
                    $email->sendPasswordReset();

                    // Set success alert
                    User::setAlert('success', 'Te hemos enviado un correo para restablecer tu contraseña');
                    
                } else {
                    User::setAlert('error', 'El email no está registrado o la cuenta no está confirmada');
                }
            }

            $alerts = User::getAlerts();
        }

        // View Render
        $router -> render('auth/forgot', [
            'title' => 'Olvidé mi contraseña',
            'alerts' => $alerts
        ]);
    }

    public static function reset(Router $router) {

        $alerts = [];
        $token = s($_GET['token']);
        $showForm = true;

        if (!$token) {
            header('Location: /');
        }

        // Find user by token
        $user = User::where('token', $token);

        if(empty($user)) {
            User::setAlert('error', 'Token no válido');
            $showForm = false;
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Add new password
                $user->sync($_POST);
                $alerts = $user->validatePassword();

                if (empty($alerts['error'])) {
                    // Hash password
                    $user->hashPassword();
                    unset($user->confirm);

                    // Reset token
                    $user->token = "";

                    // Save user in the database
                    $result = $user->save();

                    if ($result) {
                        User::setAlert('success', 'Contraseña actualizada');
                        $showForm = false;
                    }
                }
            }
        }
        
        $alerts = User::getAlerts();

        // View Render
        $router -> render('auth/reset', [
            'title' => 'Restablecer Contraseña',
            'alerts' => $alerts,
            'showForm' => $showForm
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

        // Find user by token
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