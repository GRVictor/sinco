<?php

namespace Model;

class User extends ActiveRecord {
    protected static $table = 'users';
    protected static $columns = ['id', 'name', 'email', 'password', 'token', 'confirmed'];
    protected static $alerts = ['error' => [], 'success' => []];

    public function __construct($args = []) {
        $this -> id = $args['id'] ?? null;
        $this -> name = $args['name'] ?? '';
        $this -> email = $args['email'] ?? '';
        $this -> password = $args['password'] ?? '';
        $this -> confirm = $args['confirm'] ?? '';
        $this -> token = $args['token'] ?? '';
        $this -> confirmed = $args['confirmed'] ?? 0;
    }

    public function validateAccount() {
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

    // Hashear el password
    public function hashPassword() {
        $this -> password = password_hash($this -> password, PASSWORD_BCRYPT);
    }

    // Generar token
    public function generateToken() {
        $this -> token = bin2hex(random_bytes(20));
    }

    public static function getAlerts() {
        return self::$alerts;
    }

    public static function setAlert($type, $message) {
        self::$alerts[$type][] = $message;
    }
}