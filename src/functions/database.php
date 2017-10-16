<?php

/**
 * Created by PhpStorm.
 * User: James
 * Date: 5/12/2017
 * Time: 8:41 PM
 */

class DB {

    // Access Through Instance
    private static $instance = NULL;

    // Prevent Use of new DB()
    private function __construct() {}
    private function __clone() {}

    public static function getInstance() {
        if(!isset(self::$instance)) {
            $host = "host";
            $database = "dbname";
            $username = "username";
            $password = "password";
            $charset = "utf8";
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

            self::$instance = new PDO("mysql:host=$host;dbname=$database;charset=$charset", $username, $password, $pdo_options);
        }
        return self::$instance;
    }
}

function addDatabaseEntry($object) {

    // Must be an Object
    if (!is_object($object)) {

        throw new Exception('Parameter must be an object.');

    } else {

        // Get the Database
        $db = DB::getInstance();

        // Set our Return
        $return_obj = new stdClass;

        // Set Default Arrays
        $fields = [];
        $values = [];

        // Get the table Name
        $table = $object->getTable();

        // Loop Through Object Properties/Values and Create Arrays for the Query
        foreach ($object->getProperties() as $field => $value) {
            if (!empty($value) || $value === 0) {
                $fields[] = $field;
                $values[] = $value;
            }
        }

        // Setup the Query
        $sql = "INSERT INTO $table (" . implode(", ", $fields) . ") VALUES (" . implode(", ", array_fill(0, count($fields), "?")) . ")";
        $sth = $db->prepare($sql);

        // If Successful, Return True and New ID, Else Return False and Error Message
        if ($sth->execute($values)) {
            $return_obj->success = true;
            $return_obj->new_id = $db->lastInsertId();
        } else {
            $return_obj->success = false;
            $return_obj->error = implode(",", $sth->errorInfo());
        }

        // Return Our Return Value
        return $return_obj;

    }

}
