<?php
    class User {
        private $db;
        public function __construct() {
            $this->db = new Database();
        }
        //Register User
        public function register($data) {
            $sql = 'INSERT INTO users(name, email, password) VALUES(:name, :email, :password)';
            $this->db->query($sql);
            //bind parameters
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);
            //execute
            if($this->db->execute()) {
                return true;
            } else {
                return false;
            }

        }
        //User Login
        public function login($email, $pwd) {
            $sql = 'SELECT * FROM users WHERE email = :email';
            $this->db->query($sql);
            $this->db->bind(':email', $email);
            $row = $this->db->single();
            $hashed_pwd = $row->password;
            if(password_verify($pwd, $hashed_pwd)) {
                return $row;
            } else {
                return false;
            }
        }
        //Find User By Email
        public function findUserByEmail($email) {
            $sql = 'SELECT * FROM users WHERE email = :email';
            $this->db->query($sql);
            $this->db->bind(':email', $email);
            $row = $this->db->single();
            //Check if any row is returned
            if($this->db->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }
        //Find User By Id
        public function getUserById($id) {
            $sql = 'SELECT * FROM users WHERE id = :id';
            $this->db->query($sql);
            $this->db->bind(':id', $id);
            $user = $this->db->single();
            return $user;
        }
    }