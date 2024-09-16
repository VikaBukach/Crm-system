<?php

namespace controllers\shortlink;
use controllers\pages\PageController;
use models\shortlink\ShortLinkModel;
use models\roles\Role;
use models\Check;

class ShortLinkController {
    private $ShortLinkModel;
    private $check;
    private $userId;

    public function __construct(){
        $this->ShortLinkModel = new ShortLinkModel();
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $this->check = new Check($userRole);
        $this->userId = $userId;
    }

    public function create(){
        //this->check->requirePermission();
        $userId = $this->userId;

        include 'app/views/shortlink/create.php';
    }

    public function store(){
        //this->check->requirePermission();
        if(isset($_POST['original_url']) && isset($_POST['user_id'])){
            $original_url = trim(htmlspecialchars($_POST['original_url']));
            $user_id = (int) $_POST['user_id'];

            if(!filter_var($original_url, FILTER_VALIDATE_URL)){
                echo "Invalid URL!";
                return;
            }

            if(!$_POST['short_code']){
                $shortCode = '';
                while(strlen($shortCode) < 6){
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    $randomString = '';
                    for($i = 0; $i < rand(6,10); $i++){
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                    }
                    $shortCode = substr(preg_replace('/[^a-zA-Z\d]/', '', $randomString), 0, rand(6,10));

                    if(!preg_match('/^[a-zA-Z][a-zA-Z\d-]{5,9}$/', $shortCode)){
                        $shortCode = '';
                    }
                }
            }else{
                $shortCode = $_POST['short_code'];
            }




            while($this->ShortLinkModel->isShortUrlExists($shortCode)) {
                $shortCode = '';
                while (strlen($shortCode) < 6) {
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    $randomString = '';
                    for ($i = 0; $i < rand(6, 10); $i++) {
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                    }
                    $shortCode = substr(preg_replace('/[^a-zA-Z\d]/', '', $randomString), 0, rand(6, 10));

                    if (!preg_match('/^[a-zA-Z][a-zA-Z\d-]{5,9}$/', $shortCode)) {
                        $shortCode = '';
                    }
                }
            }

            $shortUrlId = $this->ShortLinkModel->createLink($_POST['original_url'], $shortCode);
            $this->ShortLinkModel->createUserLink($user_id, $shortUrlId);
        }
        header("location: /shortlink/create");
    }



}
