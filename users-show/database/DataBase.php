<?php


class DataBase {

    public static function getDB() {
        $config = parse_ini_file(dirname(dirname(__FILE__)).'/config/config.php');
        try {
            $db = new PDO(
                'mysql:host=' . $config['db_server'] . ';dbname=' . $config['db_name'],
                $config['db_user'],
                $config['db_password'],
                [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '".$config['db_charset']."'"]
            );

           return $db;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

}