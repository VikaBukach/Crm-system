<?php

namespace controllers\users;

use models\roles\Role;
use models\users\User;
use models\Check;

//One-time password generation for connect telegram account
function generateOTP(){  //One-time password generation for connect telegram account
    $otp = rand(1000000, 9999999);
    return $otp;
}
class UsersController
{
    private $check;
    private $userId;

    public function __construct(){
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $this->check = new Check($userRole);
    }


    public function index()
    {
//        $this->check->requirePermission();

        $userModel = new User();
        $users = $userModel->readAll();

        include 'app/views/users/index.php';
    }

    public function create()
    {
//        $this->check->requirePermission();

        include 'app/views/users/create.php';
    }

    public function store()
    {
//        $this->check->requirePermission();

        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            if ($password !== $confirm_password) {
                echo "Passwords do not match";
                return;
            }

            $userModel = new User();
            $data = [
                'username' => trim(htmlspecialchars($_POST['username'])),
                'email' => trim(htmlspecialchars($_POST['email'])),
                'password' => $password,
                'role' => 1,
            ];
            $userModel->create($data);
        }

        header("Location: /users");
    }

    public function edit($params){
//        $this->check->requirePermission();

        $userModel = new User();
        $user = $userModel->read($params['id']);

        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();

        include 'app/views/users/edit.blade.php';
    }

    public function update($params)
    {
//        $this->check->requirePermission();

        $userModel = new User();
        $userModel->update($params['id'], $_POST);

        if(isset($_POST['email'])){
            $newEmail = trim(htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'));

            if($newEmail == $_SESSION['user_email']) {

                header("Location: /auth/logout");
                exit();
            }
        }
        header("Location: /users");
    }

    public function delete($params)
    {
//        $this->check->requirePermission();

        $userModel = new User();
        $userModel->delete($params['id']);

        header("Location: /users");
    }
    public function profile()
    {
//        $this->check->requirePermission();
        $user_id = $this->userId;

        $userModel = new User();
        $user = $userModel->read($user_id);

        $roleModel = new Role();
        $role = $roleModel->getRoleById($user['role']);


        $otpArr = $userModel->getLastOtpCodeByUserId($user_id);
        $isUserTelegram = $userModel->getInfoByUserIdFromTelegramTable($user_id);

        if($otpArr){
            $otpCreated = new \DateTime($otpArr['created_at']);
            $currentTime = new \DateTime();
            $interval = $otpCreated->diff($currentTime);

            $secondsDifference = $interval->days * 24 * 60 * 60;
            $secondsDifference += $interval->h * 60 * 60;
            $secondsDifference += $interval->i * 60;
            $secondsDifference += $interval->s;

            if($secondsDifference > 3600) {
                $otp = generateOTP();
                $visible = true;
            }else{
                $otp = $otpArr['otp_code'];
                $visible = false;
            }

        }else{
            $otp = generateOTP();
            $visible = true;
        }
        include 'app/views/users/profile.php';
    }


    //recording one-time password in DB
    public function otpstore()
    {
//        $this->check->requirePermission();

        if (isset($_POST['otp']) && isset($_POST['user_id'])) {

            $userModel = new User();
            $data = [
                'otp' => trim(htmlspecialchars($_POST['otp'])),
                'user_id' => trim(htmlspecialchars($_POST['user_id'])),
            ];
            $userModel->writeOTPCodeByUserId($data);
        }

        header("Location: /users/profile");
    }

}
