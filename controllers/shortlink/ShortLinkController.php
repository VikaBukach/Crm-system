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
    private $domain;

    public function __construct(){
        $this->ShortLinkModel = new ShortLinkModel();
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $this->check = new Check($userRole);
        $this->domain = $_SERVER['SERVER_NAME'];
    }

    public function index(){
        // $this->check->requirePermission();

       $short_links = $this->ShortLinkModel->getAllShortLinksByIdUser($this->userId);
       $domain = $this->domain;

        include 'app/views/shortlink/index.php';
    }

    public function create(){
        //this->check->requirePermission();
        $userId = $this->userId;

        include 'app/views/shortlink/create.php';
    }

    public function store(){
        //this->check->requirePermission();
        if(isset($_POST['original_url']) && isset($_POST['user_id']) && isset($_POST['title_link'])){
            $original_url = trim(htmlspecialchars($_POST['original_url']));
            $user_id = (int) $_POST['user_id'];
            $title_link = $_POST['title_link'];

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

            $shortUrlId = $this->ShortLinkModel->createLink($title_link, $_POST['original_url'], $shortCode);
            $this->ShortLinkModel->createUserLink($user_id, $shortUrlId);
        }
        header("location: /shortlink");
    }

    public function redirect(){
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

        $domain = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $url = $protocol . "://" . $domain . $uri;

        $code = basename(parse_url($url, PHP_URL_PATH));

        $originalURL = $this->ShortLinkModel->getOriginalLinkByShortCode($code);

        //Save statistics and data:

        $data['ip_user'] = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : null;
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ? $_SERVER['HTTP_USER_AGENT'] : null;
        $data['user_referer'] = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : null;

        $shortCode = $_SERVER['REQUEST_URI'];
        $shortCode = trim($shortCode, '/');

        if($shortCode){
            $shortLinkId = $this->ShortLinkModel->getIdlLinkByShortCode($shortCode);
            $data['short_link_id'] = $shortLinkId;

            //recording data about user in other table:
            $this->ShortLinkModel->createUserInfoByRedirectAction($data);
            //count the number of transitions:
            $existingRow = $this->ShortLinkModel->getByShortLinksId($shortLinkId);
            if($existingRow){
               $this->ShortLinkModel->updateAmount($existingRow['id'], $existingRow['amount'] + 1);
            } else {
                $this->ShortLinkModel->createNewRow($shortLinkId, 1);
            }
        }
        header("location: $originalURL");
    }

    public function delete($params){
        //this->check->requirePermission();
        $user_id = $this->userId;

        if ($this->ShortLinkModel->deleteShortLink($params['id'], $user_id)) {
            header("Location: /shortlink");
            exit();
        } else {
            echo "Error that delete the short link!";
        }
    }

    public function edit($params){
        //this->check->requirePermission();
        $user_id = $this->userId;
        $short_link = $this->ShortLinkModel->getShortLinkById($params['id'], $user_id);
        if (!$short_link) {
            echo "Error: Short link don't found!";
            return;
        }
        include 'app/views/shortlink/edit.blade.php';
    }
    public function update(){
        //this->check->requirePermission();

        if(isset($_POST['original_url']) && isset($_POST['short_link_id']) && isset($_POST['title_link'])){
            $original_url = trim(htmlspecialchars($_POST['original_url']));
            $short_link_id = $_POST['short_link_id'];
            $title_link = $_POST['title_link'];

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

            while($this->ShortLinkModel->isShortUrlExistsWithIdAndCode($short_link_id, $shortCode)) {
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

            $this->ShortLinkModel->updateLink($short_link_id, $title_link, $original_url, $shortCode);
        }
        header("location: /shortlink");
    }

    public function information($params){
        $informations = $this->ShortLinkModel->getInformationAboutEveryClick($params['id']);

        include 'app/views/shortlink/information.php';
    }
}
