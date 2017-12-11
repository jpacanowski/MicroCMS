<?php

class Users extends Controller {

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function register() {

        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'username' => trim($_POST['username']),
                'firstname' => trim($_POST['firstname']),
                'lastname' => trim($_POST['lastname']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'password_' => trim($_POST['password_']),

                'username_err' => '',
                'firstname_err' => '',
                'lastname_err' => '',
                'email_err' => '',
                'password_err' => '',
                'password__err' => '',
            ];

            // Validate Username
            if(empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }
            
            // Validate Firstname
            if(empty($data['firstname'])) {
                $data['firstname_err'] = 'Please enter first name';
            }

            // Validate Lastname
            if(empty($data['lastname'])) {
                $data['lastname_err'] = 'Please enter last name';
            }

            // Validate Email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }
            else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email address';
            }
            else if($this->userModel->findUserByEmail($data['email'])) {
                $data['email_err'] = 'Email is already taken';
            }

            // Validate Password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }
            elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if(empty($data['password_'])) {
                $data['password__err'] = 'Please confirm password';
            }
            elseif($data['password'] != $data['password_']) {
                $data['password__err'] = 'Passwords do not match';
            }

            // Make sure there are no errors
            if(empty($data['username_err']) &&
                empty($data['firstname_err']) &&
                empty($data['lastname_err']) &&
                empty($data['email_err']) &&
                empty($data['password_err']) &&
                empty($data['password__err'])) {

                // No errors. Register user
                if($this->userModel->register($data)) {
                    flash('register_success', 'You are registered and can log in.');
                    redirect('users/login');
                }
                else {
                    die('Could not register the user');
                }
            }
            else {
                // Load view with errors
                $this->view('users/register', $data);
            }
        }
        else if($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Load form
            $data = [
                'name' => '',
                'email' => '',
                'pass' => '',
                'pass_confirm' => '',
                'name_err' => '',
                'email_err' => '',
                'pass_err' => '',
                'pass_confirm_err' => '',
            ];

            $this->view('users/register', $data);
        }
    }

    public function login() {
        
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),

                'username_err' => '',
                'password_err' => '',
            ];

            // Validate Username
            if(empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }
            //else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            //    $data['email_err'] = 'Please enter a valid email address';
            //}
            else if(!$this->userModel->findUserByEmail($data['username'])) {
                $data['username_err'] = 'No user found';
            }

            // Validate Password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }
            elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Make sure there are no errors
            if(empty($data['username_err']) && empty($data['password_err'])) {

                // No errors. Check and set logged in user
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                if($loggedInUser) {
                    // Create session
                    $this->createUserSession($loggedInUser);
                }
                else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('users/login', $data);
                }
            }
            else {
                // Load view with errors
                $this->view('users/login', $data);
            }
        }
        else if($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Load form
            $data = [
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => '',
            ];

            $this->view('users/login', $data);
        }
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_firstname'] = $user->firstname;
        $_SESSION['user_lastname'] = $user->lastname;
        redirect('dashboard');
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_firstname']);
        unset($_SESSION['user_lastname']);
        session_destroy();
        redirect('users/login');
    }
}