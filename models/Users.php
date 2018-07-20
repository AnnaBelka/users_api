<?php
require_once('models/EntityFactory.php');

    class Users extends CreateFactory {
        private $salt = '8e86a279d6e182b3c811c559e6b15484';
        private $api_url = 'api/users.php';
        private $content_type = 'json';


        public function create() {
            if (!empty($_POST)) {

                $errors = array();
                $user = new stdClass;
                if (!empty($_POST['name'])) {
                    $user->name = trim($_POST['name']);
                }
                if (!empty($_POST['surname'])) {
                    $user->surname = trim($_POST['surname']);
                }
                if (!empty($_POST['email'])) {
                    $user->email = trim($_POST['email']);
                    $re = '/(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})/m';
                    preg_match_all($re, $user->email, $matches, PREG_SET_ORDER, 0);
                    if(!$matches) {
                        $errors['errors'][] = 'error_email';
                    }

                    $query = "SELECT count(*) as count as count FROM u_users WHERE `email`='$user->email'";

                    $user_exists = $this->db->query($query);
                    if($user_exists['count']) {
                        $errors['errors'][] = 'error_user_exists_email';
                    }

                }

                if (!empty($_POST['login'])) {
                    $user->login = trim($_POST['login']);
                    $query = "SELECT count(*) as count as count FROM u_users WHERE `login`= '$user->login'";

                    $user_exists = $this->db->query($query);

                    if($user_exists['count']) {
                        $errors['errors'][] = 'error_user_exists_login';
                    }
                }

                if (!empty($_POST['password'])) {
                    $user->password = trim($_POST['password']);
                    $re = '/((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{3,})/m';
                    preg_match_all($re, $user->password, $matches, PREG_SET_ORDER, 0);
                    if(!$matches) {
                        $errors['errors'][] = 'error_password';
                    }
                    $user->password = md5($this->salt.$user->password.md5($user->password));
                } else {
                    $errors['errors'][] = 'error_password';
                }

                if (!empty($errors)) {
                    return Users::view()->assign('user', $errors);
                } else {
                    $query = "INSERT INTO `u_users`(`name`, `surname`, `email`, `login`, `password`) VALUES ('$user->name','$user->surname','$user->email','$user->login','$user->password')";

                    $this->db->query($query);
                    $user_id = $this->db->lastInsertId();
                    unset($user);
                    if (!empty($user_id)) {
                        $user_query = $this->db->query('SELECT `id`, `name`, `surname`, `email`, `login` FROM u_users WHERE id='.$user_id);
                        foreach ($user_query as $u) {
                            $user['user']['id'] = $u['id'];
                            $user['user']['name'] = $u['name'];
                            $user['user']['surname'] = $u['surname'];
                            $user['user']['email'] = $u['email'];
                            $user['user']['login'] = $u['login'];
                        }
                        $post_data = $user['user'];
                        $this->api($this->api_url, $this->content_type, $post_data);
                    }
                    return Users::view()->assign('user', $user);
                }



            } else {
                Users::view()->assign('user');
            }
        }

        public function read() {
            $users = $this->db->query('SELECT `id`, `name`, `surname`, `email`, `login` FROM u_users');
            return Users::view()->assign('users', $users);

        }

        public function update($id) {
            if (!empty($_POST) && is_int($id)) {
                $errors = array();
                $user_update = new stdClass;
                if (!empty($_POST['name'])) {
                    $user_update->name = trim($_POST['name']);
                }
                if (!empty($_POST['surname'])) {
                    $user_update->surname = trim($_POST['surname']);
                }
                if (!empty($_POST['email'])) {
                    $user_update->email = trim($_POST['email']);
                    $re = '/(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})/m';
                    preg_match_all($re, $user_update->email, $matches, PREG_SET_ORDER, 0);
                    if(!$matches) {
                        $errors[] = 'error_email';
                    }

                    $query = "SELECT count(*) as count as count FROM u_users WHERE `email`='$user_update->email' AND `id`!='$id'";

                    $user_exists = $this->db->query($query);
                    if($user_exists['count']) {
                        $errors[] = 'error_user_exists_email';
                    }

                }

                if (!empty($_POST['login'])) {
                    $user_update->login = trim($_POST['login']);
                    $query = "SELECT count(*) as count as count FROM u_users WHERE `login`= '$user_update->login' AND `id`!='$id'";

                    $user_exists = $this->db->query($query);

                    if($user_exists['count']) {
                        $errors[] = 'error_user_exists_login';
                    }
                }

                if (!empty($_POST['password'])) {
                    $user_update->password = trim($_POST['password']);
                    $re = '/((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{3,})/m';
                    preg_match_all($re, $user_update->password, $matches, PREG_SET_ORDER, 0);
                    if(!$matches) {
                        $errors[] = 'error_password';
                    }
                    $user_update->password = md5($this->salt.$user_update->password.md5($user_update->password));
                }
                $user = array();
                $user['name'] = $user_update->name;
                $user['surname'] = $user_update->surname;
                $user['email'] = $user_update->email;
                $user['login'] = $user_update->login;
                $user['user']['password'] = $user_update->password;
                if (!empty($errors)) {
                    $content = array();
                    $content['user'] = $user;
                    $content['errors'] = $errors;
                    return Users::view()->assign('user', $content);
                } else {

                    $query = "UPDATE `u_users` SET `name`='$user_update->name',`surname`='$user_update->surname',`email`='$user_update->email',`login`='$user_update->login',`password`='$user_update->password' WHERE id=".$id;

                    $this->db->query($query);
                    $user_id = $this->db->lastInsertId();

                }
            }
            if (is_int($id) || is_int($user_id)) {
                $user_query = $this->db->query('SELECT `id`, `name`, `surname`, `email`, `login` FROM u_users WHERE id='.$id);
                foreach ($user_query as $u) {
                    $user['user']['id'] = $u['id'];
                    $user['user']['name'] = $u['name'];
                    $user['user']['surname'] = $u['surname'];
                    $user['user']['email'] = $u['email'];
                    $user['user']['login'] = $u['login'];
                }
                return Users::view()->assign('user', $user);
            }
        }

        public function delete($id) {
            if (is_int($id)) {
                $user = $this->db->query('SELECT `id` FROM u_users WHERE id='.$id);
                if (!empty($user)) {
                    $query = "DELETE FROM `u_users` WHERE id=".$id;
                    $this->db->query($query);
                }
            }
            header("Location: /users/read");
        }
    }