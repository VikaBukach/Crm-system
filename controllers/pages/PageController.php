<?php

namespace controllers\pages;

use models\pages\PageModel;
use models\roles\Role;
use models\Check;


class PagesController
{
    private $check;

    public function __construct(){
        $this->check = new Check();
    }


    public function index()
    {
        $slug = $this->check->getCurrentUrlSlug();

        if(!$this->checkPermission($slug)){
            $path = '/';
            header("Location: $path");
            return;
        }


        $pageModel = new PageModel();
        $pages = $pageModel->getAllPages();

        include 'app/views/pages/index.php';
    }

    public function create()
    {
        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();

        include 'app/views/pages/create.php';
    }

    public function store()
    {
        if (isset($_POST['title']) && isset($_POST['slug'])) {
            $title = trim($_POST['title']);
            $slug = trim($_POST['slug']);
            $roles = implode(",", $_POST['roles']);

            if (empty($title) || empty($slug) || empty($roles)) {
                echo "Title and Slug fields are required";
                return;
            }

            $pageModel = new PageModel();
            $pageModel->createPage($title, $slug, $roles);
        }
        $path = '/' . 'pages';
        header("Location: $path");
    }

    public function edit($params)
    {
        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();

        $pageModel = new PageModel();
        $page = $pageModel->getPageById($params['id']);


        if (!$page) {
            echo "Page not found";
            return;
        }

        include 'app/views/pages/edit.php';
    }

    public function update($params)
    {
        if (isset($params['id']) && isset($_POST['title']) && isset($_POST['slug']) && isset($_POST['roles']) ) {
            $id = trim($params['id']);
            $title = trim($_POST['title']);
            $slug = trim($_POST['slug']);
            $roles = implode(",", $_POST['roles']);

            if (empty($title) || empty($slug) || empty($roles)) {
                echo "Title, Slug and Roles fields are required";
                return;
            }

            $pageModel = new PageModel();
            $pageModel->updatePage($id, $title, $slug, $roles);
        }
        $path = '/' . 'pages';
        header("Location: $path");
    }

    public function delete($params)
    {
        $pageModel = new PageModel();
        $pageModel->deletePage($params['id']);

        $path = '/' . 'pages';
        header("Location: $path");
    }

    public function checkPermission($slug)
    {
        $pageModel = new PageModel();
        $page = $pageModel->findBySlug($slug);
        if(!$page) {
            return false;
        }
        //get role permission for the page
        $allowedRoles = explode(',', $page['role']);

        //checking if the current user has access to the page
        if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], $allowedRoles)) {
            return true;
        }else{
            return false;
        }
    }
}


