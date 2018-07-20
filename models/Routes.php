<?php
    class Routes {

        private function getUri() {
            if (!empty($_SERVER['REQUEST_URI'])) {
                return trim($_SERVER['REQUEST_URI'], '/');
            }
        }

        public function run() {
            $uri = $this->getUri();
            $segments = explode('/',$uri);
            $file = ucfirst(trim($segments[0])).'.php';
            $class = ucfirst(trim($segments[0]));
            $method = trim($segments[1]);

            if (empty($uri)) {
                require_once(dirname(__FILE__).'/EntityFactory.php');
                CreateFactory::index();
            } elseif (file_exists(dirname(__FILE__).'/'.$file)) {
                require_once(dirname(__FILE__).'/'.$file);
                if (class_exists($class) && method_exists($class, $method)) {
                    $entity = new $class;

                    if (isset($segments[2])){
                        $id = intval($segments[2]);
                        $entity->$method($id);
                    } else {
                        $entity->$method();
                    }
                } else {
                    /*Нет такого класса или метода*/
                    // Иначе страница об ошибке
                    header("http/1.0 404 not found");
                    require_once(dirname(__FILE__).'/EntityFactory.php');
                    CreateFactory::not_found();
                }
            } else {
                /*Нет такого класса*/
                // Иначе страница об ошибке
                header("http/1.0 404 not found");
                require_once(dirname(__FILE__).'/EntityFactory.php');
                CreateFactory::not_found();
            }

        }

    }