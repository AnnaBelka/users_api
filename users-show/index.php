<?PHP

session_start();

require_once('models/Routes.php');

$router = new Routes();
$router->run();

