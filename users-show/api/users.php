<?php
require_once(dirname(dirname(__FILE__)).'/database/DataBase.php');

$log_file = '';
$db = DataBase::getDB();
foreach ($db->query("SELECT `value` FROM `u_settings` WHERE `name`='AccessToken'") as $res){
    $AccessToken = $res['value'];
}

$api_AccessToken = $_SERVER["HTTP_AUTHORIZATION"];
// получить HTTP-метод, путь и тело запроса
$method = $_SERVER['REQUEST_METHOD'];
$log_file .= 'Время запуска: '.date("Y-m-d H:i:s")."\n";
$log_file .= '$api_AccessToken: '.$api_AccessToken."\n";

if ($api_AccessToken == "AccessToken ".$AccessToken && $method=='POST') {
    $user = new Stdclass;
    $entity_body = file_get_contents('php://input');
    $user = json_decode($entity_body);

    if ($user->id) {
        $query = "UPDATE `u_users` SET `name`='$user->name',`surname`='$user->surname',`email`='$user->email',`login`='$user->login',`password`='$user->password' WHERE id=" . $user->id;

        $db->query($query);
        $request['status'] = 'OK';
    } elseif($user->delete_id) {
        $id = $user->delete_id;
        $query = "DELETE FROM `u_users` WHERE id=".$id;

        $db->query($query);
        $request['status'] = 'OK';
    } else {
        $query = "INSERT INTO `u_users`(`name`, `surname`, `email`, `login`, `password`) VALUES ('$user->name','$user->surname','$user->email','$user->login','$user->password')";

        $db->query($query);
        $request['status'] = 'OK';

    }

    if (stristr($_SERVER["HTTP_CONTENT_TYPE"],"application/xml")) {

    } elseif (stristr($_SERVER["HTTP_CONTENT_TYPE"],"application/json")) {
        $request = json_encode($request);
    }

} else {
    $request['status'] = 'error';
    if (stristr($_SERVER["HTTP_CONTENT_TYPE"],"application/xml")) {

    } elseif (stristr($_SERVER["HTTP_CONTENT_TYPE"],"application/json")) {
        $request = json_encode($request);
    }
}

file_put_contents(dirname(__FILE__).'/log.txt', $log_file, FILE_APPEND);

return $request;
