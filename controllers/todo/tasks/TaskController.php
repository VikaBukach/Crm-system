<?php

namespace controllers\todo\tasks;

use models\todo\tasks\TaskModel;
use models\todo\tasks\TagsModel;
use models\todo\category\CategoryModel;
use models\Check;

class TaskController
{
    private $check;
    public function __construct(){
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new Check($userRole);
    }
    public function index()
    {
        $this->check->requirePermission();

        $taskModel = new TaskModel();
        $tasks = $taskModel->getAllTasks();

        include 'app/views/todo/tasks/index.php';
    }

    public function create()
    {
        $this->check->requirePermission();

        $todoCategoryModel = new CategoryModel();
        $categories = $todoCategoryModel->getAllCategoriesWithUsability();

        include 'app/views/todo/tasks/create.php';
    }

    public function store()
    {

        $this->check->requirePermission();

        if (isset($_POST['title']) && isset($_POST['category_id']) && isset($_POST['finish_date'])) {
            $data['title'] = trim($_POST['title']);
            $data['category_id'] = trim($_POST['category_id']);
            $data['finish_date'] = trim($_POST['finish_date']);
            $data['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            $data['status'] = 'new';
            $data['priority'] = 'low';

            $taskModel = new TaskModel();
            $taskModel->createTask($data);
        }

        header("Location: /todo/tasks");
    }

    public function edit($params)
    {
        $this->check->requirePermission();

        $taskModel = new TaskModel();
        $task = $taskModel->getTaskById($params['id']);

        $todoCategoryModel = new CategoryModel();
        $categories = $todoCategoryModel->getAllCategoriesWithUsability();

        if (!$task) {
            echo "Task not found";
            return;
        }

        $tagsModel = new TagsModel();
        $tags = $tagsModel->getTagsByTaskId($task['id']);

        include 'app/views/todo/tasks/edit.php';
    }

    public function update($params)
    {
        $this->check->requirePermission();

        if (isset($params['id']) && isset($_POST['title']) && isset($_POST['description'])) {
            $id = trim($params['id']);
            $title = trim(htmlspecialchars($_POST['title']));
            $description = trim(htmlspecialchars($_POST['description']));
            $usability = isset($_POST['usability']) ? $_POST['usability'] : 0;

            if (empty($title) || empty($description)) {
                echo "Role and Title are required";
                return;
            }

            $todoCategoryModel = new CategoryModel();
            $todoCategoryModel->updateCategory($id, $title, $description, $usability);
        }

        header("Location: /todo/category");
    }

    public function delete($params)
    {
        $this->check->requirePermission();

        $todoCategoryModel = new CategoryModel();
        $todoCategoryModel->deleteCategory($params['id']);

        header("Location: /todo/category");
    }
}


