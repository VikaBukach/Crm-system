<?php
require_once 'app/models/AuthUser.php';

class AuthController {
    public function register() {

        include 'app/views/users/register.php';
    }

    public function store() {
        if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                echo "Passwords do not match";
                return;
            }

            $userModel = new User();
            $data = [
                'username'=> $_POST['username'],
                'email'=> $_POST['email'],
                'password'=> password_hash($password, PASSWORD_DEFAULT),
                'role'=> 1,
            ];
            $userModel->create($data);
        }
        header("Location: index.php?page=login");
    }

    public function login(){
        include 'app/views/users/login.php';
    }

    public  function authentication(){
        $authModel = new AuthUser();

        if(isset($_POST['email']) && isset($_POST['password'])){
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $authModel->findByEmail($email);

            if($user && password_verify($password, $user['password'])){
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                header("Location: index.php");
            }else{
                echo "Invalid email or password";
            }
        }
    }

    public function logout(){
        session_start();
        session_unset();
        session_destroy();

        header('Location: index.php');
    }
}

