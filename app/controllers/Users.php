<?php
    class Users extends Controller {
        private $userModel;
        public function __construct() {
            $this->userModel = $this->model('User');
        }
        public function register() {
            //Process Form Data
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Sanitize $_POST array after getting form data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                //Init Data
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];
                //validate email
                if(empty($data['email'])) {
                    $data['email_err'] = 'Please enter an email.';
                } else {
                    if($this->userModel->findUserByEmail($data['email'])) {
                        $data['email_err'] = 'This email is already registered.';
                    }
                }
                //validate name
                if(empty($data['name'])) {
                    $data['name_err'] = 'Please enter name.';
                }
                //validate password
                if(empty($data['password'])) {
                    $data['password_err'] = 'Please enter a password.';
                } else if(strlen($data['password']) < 6) {
                    $data['password_err'] = 'Please enter a password with atleast six characters.';
                }
                //validate confirm password
                if(empty($data['confirm_password'])) {
                    $data['confirm_password_err'] = 'Please enter confirm password.';
                } else if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Confirm password must match original password!';
                }
                //Validate All Error Fields are empty
                if( empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) ) {
                    //Hash Password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    //register user
                    if($this->userModel->register($data)) {
                        flash('register_success', 'You are registered. Kindly Login!');
                        redirect('users/login');
                    } else {
                        die('Registration Unsuccessful!! Something Went Wrong.');
                    }
                } else {
                    $this->view('users/register', $data);
                }

            } else {
                //Load Form
                //Init Data
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];
                //Load register view
                $this->view('users/register', $data);
            }
        }
        public function login() {
                //Process Form Data
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    //Sanitize $_POST array after getting form data
                    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                    //Init Data
                    $data = [
                        'email' => trim($_POST['email']),
                        'password' => trim($_POST['password']),
                        'email_err' => '',
                        'password_err' => '',
                    ];
                    //validate email
                    if(empty($data['email'])) {
                        $data['email_err'] = 'Please enter an email.';
                    } else {
                        //Check For User/Email
                        if($this->userModel->findUserByEmail($data['email'])) {
                            //User Found
                        } else {
                            //User Not Found
                            $data['email_err'] = 'No such user found!';
                        }
                    }
                    //validate password
                    if(empty($data['password'])) {
                        $data['password_err'] = 'Please enter a password.';
                    }
                    
                    //Validate All Error Fields are empty
                    if( empty($data['email_err']) && empty($data['password_err'])) { 
                        //Check and set logged in user
                        $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                        if($loggedInUser) {
                            //Create Session Variables
                            $this->createUserSession($loggedInUser);
                        } else {
                            $data['password_err'] = 'Incorrect Password.';
                            $this->view('users/login', $data);
                        }
                        
                    } else {
                        $this->view('users/login', $data);
                    }

                } else {
                    //Load Form
                    //Init Data
                    $data = [
                        'email' => '',
                        'password' => '',
                        'email_err' => '',
                        'password_err' => '',
                    ];
                    //Load register view
                    $this->view('users/login', $data);
                }
        }
        public function createUserSession($user) {
            session_start();
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email;
            redirect('posts');

        }
        public function logout() {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
            unset($_SESSION['user_email']);
            session_destroy();
            redirect('users/login');
        }
    }