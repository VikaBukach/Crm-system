<?php

namespace controllers\roles;
use models\roles\Role;

class RoleController
{
    public function index()
    {
        $roleModel = new Role();
        $roles = $roleModel->getAllRoles();

        include 'app/views/roles/index.php';
    }

    public function create()
    {
        include 'app/views/roles/create.php';
    }

    public function store()
    {
        if (isset($_POST['role_name']) && isset($_POST['role_description'])) {
            $role_name = trim($_POST['role_name']);
            $role_description = trim($_POST['role_description']);

            if (empty($role_name)) {
                echo "Role name is required";
                return;
            }

            $roleModel = new Role();
            $roleModel->createRole($role_name, $role_description);
        }
        $path = '/roles';
        header("Location: $path");
    }

    public function edit($params)
    {
        $roleModel = new Role();
        $role = $roleModel->getRoleById($params['id']);

        if (!$role) {
            echo "Role not found";
            return;
        }

        include 'app/views/roles/edit.php';
    }

    public function update()
    {
        if (isset($_POST['id']) && isset($_POST['role_name']) && isset($_POST['role_description'])) {
            $id = trim($_POST['id']);
            $role_name = trim($_POST['role_name']);
            $role_description = trim($_POST['role_description']);

            if (empty($role_name)) {
                echo "Role name is required";
                return;
            }

            $roleModel = new Role();
            $roleModel->updateRole($id, $role_name, $role_description);
        }
        $path = '/roles';
        header("Location: $path");
    }

    public function delete()
    {
        $roleModel = new Role();
        $roleModel->deleteRole($_GET['id']);

        $path = '/roles';
        header("Location: $path");
    }
}

