<?php

namespace Model;

class User extends ActiveRecord {
    protected static $table = 'users';
    protected static $columns = ['id', 'name', 'email', 'password', 'token', 'confirmed'];
    protected static $alerts = ['error' => [], 'success' => []];

    public $id;
    public $name;
    public $email;
    public $password;
    public $confirm;
    public $token;
    public $confirmed;
    // new password confirmation
    public $currentPassword;
    public $newPassword;
    public $confirmPassword;

    public function __construct($args = []) {
        $this -> id = $args['id'] ?? null;
        $this -> name = $args['name'] ?? '';
        $this -> email = $args['email'] ?? '';
        $this -> password = $args['password'] ?? '';
        $this -> confirm = $args['confirm'] ?? ''; // It is part of the constructor but it is not part of the data we save in the database
        $this -> token = $args['token'] ?? '';
        $this -> confirmed = $args['confirmed'] ?? 0;

        // new password confirmation
        $this -> currentPassword = $args['currentPassword'] ?? ''; // It is part of the constructor but it is not part of the data we save in the database
        $this -> newPassword = $args['newPassword'] ?? ''; // It is part of the constructor but it is not part of the data we save in the database
        $this -> confirmPassword = $args['confirmPassword'] ?? ''; // It is part of the constructor but it is not part of the data we save in the database
    }

    public function validateLogin() : array {
        if (!$this -> email) {
            self::$alerts['error'][] = 'Debes ingresar tu email';
        } else if (!filter_var($this -> email, FILTER_VALIDATE_EMAIL)) {
            self::$alerts['error'][] = 'Debes ingresar un email válido';
        }

        if (!$this -> password) {
            self::$alerts['error'][] = 'Debes ingresar tu contraseña';
        }

        return self::$alerts;
    }

    public function validateAccount() : array {
        if (!$this -> name) {
            self::$alerts['error'][] = 'Debes ingresar tu nombre';
        }

        if (!$this -> email) {
            self::$alerts['error'][] = 'Debes ingresar tu email';
        }

        if (!filter_var($this -> email, FILTER_VALIDATE_EMAIL)) {
            self::$alerts['error'][] = 'Debes ingresar un email válido';
        }

        if (!$this -> password) {
            self::$alerts['error'][] = 'Debes ingresar tu contraseña';
        }

        if (strlen($this -> password) < 8) {
            self::$alerts['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }

        if ($this -> password !== $this -> confirm) {
            self::$alerts['error'][] = 'Las contraseñas no coinciden';
        }

        return self::$alerts;
    }

    public function validateEmail() : array {
        
        if (!$this -> email) {
            self::$alerts['error'][] = 'Debes ingresar tu email';
        } else if (!filter_var($this -> email, FILTER_VALIDATE_EMAIL)) {
            self::$alerts['error'][] = 'Debes ingresar un email válido';
        }

        return self::$alerts;
    }

    public function validatePassword() : array {
        if (!$this -> password) {
            self::$alerts['error'][] = 'Debes ingresar tu contraseña';
        }

        if (strlen($this -> password) < 8) {
            self::$alerts['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }

        if ($this -> password !== $this -> confirm) {
            self::$alerts['error'][] = 'Las contraseñas no coinciden';
        }

        return self::$alerts;
    }

    public function validateProfile() : array {
        if (!$this -> name) {
            self::$alerts['error'][] = 'Debes ingresar tu nombre';
        } 

        if (!$this -> email) {
            self::$alerts['error'][] = 'Debes ingresar tu email';
        } else if (!filter_var($this -> email, FILTER_VALIDATE_EMAIL)) {
            self::$alerts['error'][] = 'Debes ingresar un email válido';
        }

        return self::$alerts;
    }

    public function newPassword() : array {
        if(!$this -> currentPassword) {
            self::$alerts['error'][] = 'Debes ingresar tu contraseña actual';
        }

        // if ($this -> password && !password_verify($this -> password, $this -> password)) {
        //     self::$alerts['error'][] = 'La contraseña anterior es incorrecta';
        // }

        if(!$this -> newPassword) {
            self::$alerts['error'][] = 'Debes ingresar tu nueva contraseña';
        }

        if (strlen($this -> newPassword) < 8) {
            self::$alerts['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }

        if ($this -> newPassword !== $this -> confirmPassword) {
            self::$alerts['error'][] = 'Las contraseñas no coinciden';
        }

        return self::$alerts;
    } 

    public function verifyPassword() : bool {
       return password_verify($this -> currentPassword, $this -> password);
    }

    // Hashear el password
    public function hashPassword() : void {
        $this -> password = password_hash($this -> password, PASSWORD_BCRYPT);
    }

    // Generar token
    public function generateToken() :void {
        $this -> token = bin2hex(random_bytes(20));
    }

    public static function getAlerts() : array {
        return self::$alerts;
    }

    public static function setAlert($type, $message) : void {
        self::$alerts[$type][] = $message;
    }
}