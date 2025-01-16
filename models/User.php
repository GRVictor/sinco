<?php

namespace Model;

class User extends ActiveRecord {
    protected static $table = 'users';
    protected static $columns = ['id', 'name', 'email', 'password', 'token', 'confirmed'];

    public function __construct($args = []) {
        $this -> id = $args['id'] ?? null;
        $this -> name = $args['name'] ?? '';
        $this -> email = $args['email'] ?? '';
        $this -> password = $args['password'] ?? '';
        $this -> token = $args['token'] ?? '';
        $this -> confirmed = $args['confirmed'] ?? 0;
    }

    public function validateAccount() {
        if (!$this -> name) {
            self::$alerts[] = 'Debes añadir un nombre';
        }

        if (!$this -> email) {
            self::$alerts[] = 'Debes añadir un email';
        }

        if (!$this -> password) {
            self::$alerts[] = 'Debes añadir una contraseña';
        }

        if (!filter_var($this -> email, FILTER_VALIDATE_EMAIL)) {
            self::$alerts[] = 'Email no válido';
        }

        return self::$alerts;
    }
}