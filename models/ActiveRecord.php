<?php
namespace Model;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $table = '';
    protected static $columns = [];

    // Alertas y Mensajes
    protected static $alerts = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlert($type, $message) {
        static::$alerts[$type][] = $menssage;
    }
    // Validación
    public static function getAlerts() {
        return static::$alerts;
    }

    public function validate() {
        static::$alerts = [];
        return static::$alerts;
    }

    // Registros - CRUD
    public function save() {
        $result = '';
        if(!is_null($this->id)) {
            // actualizar
            $result = $this->update();
        } else {
            // Creando un nuevo registro
            $result = $this->create();
        }
        return $result;
    }

    public static function all() {
        $query = "SELECT * FROM " . static::$table;
        $result = self::querySQL($query);
        return $result;
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$table  ." WHERE id = ${id}";
        $result = self::querySQL($query);
        return array_shift( $result );
    }

    // Obtener Registro
    public static function get($limit) {
        $query = "SELECT * FROM " . static::$table . " LIMIT ${limit}";
        $result = self::querySQL($query);
        return array_shift( $result ) ;
    }

    // Busqueda Where con Columna 
    public static function where($column, $value) {
        $query = "SELECT * FROM " . static::$table . " WHERE ${column} = '${value}' ";
        $result = self::querySQL($query);
        return array_shift( $result ) ;
    }

    // searh all records that belong to an id
    public static function belongsTo($column, $value) {
        $query = "SELECT * FROM " . static::$table . " WHERE ${column} = '${value}' ";
        $result = self::querySQL($query);
        return $result;
    }

    // SQL para Consultas Avanzadas.
    public static function SQL($consult) {
        $query = $consult;
        $result = self::querySQL($query);
        return $result;
    }
 
    // crea un nuevo registro
    public function create() {
        // Sanitizar los datos
        $attributes = $this->sanitizeAttributes();

        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$table . " ( ";
        $query .= join(', ', array_keys($attributes));
        $query .= " ) VALUES (' "; 
        $query .= join("', '", array_values($attributes));
        $query .= " ') ";

        // Resultado de la consulta
        $result = self::$db->query($query);

        return [
           'result' =>  $result,
           'id' => self::$db->insert_id
        ];
    }

    public function update() {
        // Sanitizar los datos
        $attributes = $this->sanitizeAttributes();

        // Iterar para ir agregando cada campo de la BD
        $values = [];
        foreach($attributes as $key => $value) {
            $values[] = "{$key}='{$value}'";
        }

        $query = "UPDATE " . static::$table ." SET ";
        $query .=  join(', ', $values );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        // debuguear($query);

        $result = self::$db->query($query);
        return $result;
    }

    // Eliminar un registro - Toma el ID de Active Record
    public function delete() {
        $query = "DELETE FROM "  . static::$table . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $result = self::$db->query($query);
        return $result;
    }

    public static function querySQL($query) {
        // Consultar la base de datos
        $result = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($record = $result->fetch_assoc()) {
            $array[] = static::createObjet($record);
        }

        // liberar la memoria
        $result->free();

        // retornar los resultados
        return $array;
    }

    protected static function createObjet($record) {
        $objet = new static;

        foreach($record as $key => $value ) {
            if(property_exists( $objet, $key  )) {
                $objet->$key = $value;
            }
        }

        return $objet;
    }

    // Identificar y unir los atributos de la BD
    public function attributes() {
        $attributes = [];
        foreach(static::$columns as $column) {
            if($column === 'id') continue;
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    public function sanitizeAttributes() {
        $attributes = $this->attributes();
        $sanitized = [];
        foreach($attributes as $key => $value ) {
            $sanitized[$key] = self::$db->escape_string($value);
        }
        return $sanitized;
    }

    public function sync($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }
}