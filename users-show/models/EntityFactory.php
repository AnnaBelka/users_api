<?php

require_once(dirname(dirname(__FILE__)).'/database/DataBase.php');
require_once('View.php');

abstract class EntityFactory {

}

class CreateFactory extends EntityFactory {

    public $db;
    public $view;

    public function __construct() {
        return $this->db = DataBase::getDB();
    }

    public static function view() {
        return new View();
    }

    public static function index() {
        CreateFactory::view()->assign();
    }

    public function read() {}

}