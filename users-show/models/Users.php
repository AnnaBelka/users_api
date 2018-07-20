<?php
require_once('models/EntityFactory.php');

    class Users extends CreateFactory {

        public function read() {
            $users = $this->db->query('SELECT `id`, `name`, `surname`, `email`, `login` FROM u_users');
            return Users::view()->assign('users', $users);

        }

    }