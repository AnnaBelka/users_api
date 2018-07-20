<?php
require_once(dirname(dirname(__FILE__)).'/database/DataBase.php');

class Api {
    public $db;
    public $AccessToken;

    public function __construct() {
        $log_file = '';
        $this->db = DataBase::getDB();
        foreach ($this->db->query("SELECT `value` FROM `u_settings` WHERE `name`='AccessToken'") as $res){
            $this->AccessToken = $res['value'];
        }

        $api_AccessToken = "AccessToken ".$_SERVER["AccessToken"];
        // получить HTTP-метод, путь и тело запроса
        $method = $_SERVER['REQUEST_METHOD'];
        $log_file .= 'Время запуска: '.date("Y-m-d H:i:s")."\n";
        $log_file .= '$api_AccessToken: '.$api_AccessToken."\n";

        if ($api_AccessToken == $this->AccessToken && $method=='POST') {
            $user = new Stdclass;
            $data = json_decode($_POST);
            $user->name = $data['name'];
            $user->surname = $data['surname'];
            $user->email = $data['email'];
            $user->login = $data['login'];
            $user->password = $data['password'];
            if ($data['id']) {
                $user->id = $data['id'];
                $query = "UPDATE `u_users` SET `name`='$user->name',`surname`='$user->surname',`email`='$user->email',`login`='$user->login',`password`='$user->password' WHERE id=" . $user->id;
                $this->db->query($query);
                $request['status'] = 'OK';
            } elseif($data['delete_id']) {
                $id = $data['delete_id'];
                $query = "DELETE FROM `u_users` WHERE id=".$id;
                $this->db->query($query);
                $request['status'] = 'OK';
            } else {
                $query = "INSERT INTO `u_users`(`name`, `surname`, `email`, `login`, `password`) VALUES ('$user->name','$user->surname','$user->email','$user->login','$user->password')";
                $this->db->query($query);
                $request['status'] = 'OK';

            }

            if (stristr($_SERVER["HTTP_ACCEPT"],"application/xml")) {

            } elseif (stristr($_SERVER["HTTP_ACCEPT"],"application/json")) {
                $request = json_encode($request);
            }

        } else {
            $request['status'] = 'error';
            if (stristr($_SERVER["HTTP_ACCEPT"],"application/xml")) {

            } elseif (stristr($_SERVER["HTTP_ACCEPT"],"application/json")) {
                $request = json_encode($request);
            }
        }
        file_put_contents(dirname(__FILE__).'/log.txt', $log_file, FILE_APPEND);
        return $request;
    }
}

$api_user = new Api;
?>