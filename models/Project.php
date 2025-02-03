<?php

namespace Model;

class Project extends ActiveRecord {
    protected static $table = 'projects';
    protected static $columns = ['id', 'project', 'url', 'ownerId'];

    public $id;
    public $project;
    public $url;
    public $ownerId;

    public function __construct($args = []) {
        $this -> id = $args['id'] ?? null;
        $this -> project = $args['project'] ?? '';
        $this -> url = $args['url'] ?? '';
        $this -> ownerId = $args['ownerId'] ?? '';
    }

    public function validateProject() {

        if(!$this -> project) {
            self::$alerts['error'][] = 'El nombre del proyecto es obligatorio';
        }

        return self::$alerts;
    }

}