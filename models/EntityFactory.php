<?php

require_once(dirname(dirname(__FILE__)).'/database/DataBase.php');
require_once('View.php');

abstract class EntityFactory {
    abstract public function api($url, $content_type, $post_data = array());
}

class CreateFactory extends EntityFactory {

    public $db;
    public $view;
    private $api_url;
    private $AccessToken;

    public function __construct() {
        return $this->db = DataBase::getDB();
    }

    public static function view() {
        return new View();
    }

    public function api($url, $content_type, $post_data = array()) {
        foreach ($this->db->query("SELECT `value` FROM `u_settings` WHERE `name`='api_url'") as $res) {
            $this->api_url = $res['value'];

        }
        foreach ($this->db->query("SELECT `value` FROM `u_settings` WHERE `name`='AccessToken'") as $res){
            $this->AccessToken = $res['value'];
        }
        $curl_url = $this->api_url.'/'.$url;
        if ($content_type == 'json') {
            $headers_content_type = 'application/json;charset=UTF-8';
        } elseif ($content_type == 'xml') {
            $headers_content_type = 'application/xml;charset=UTF-8';
        }
        //Заголовки запроса
        $headers = array(
            "Authorization: AccessToken ".$this->AccessToken,
            "Content-Type: ".$headers_content_type
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, 1); //передача данных методом POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); //тут переменные которые будут переданы методом POST

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            echo "cURL Error #:" . $error;
        } else {
            return json_decode($response);
        }

    }

    public static function index() {
        CreateFactory::view()->assign();
    }

    public function create() {}

    public function read() {}

    public function update($id) {}

    public function delete($id) {}

    public function not_found() {
        CreateFactory::view()->assign('404');
    }
}