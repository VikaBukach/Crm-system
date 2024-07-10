<?php

namespace controllers\todo\tasks;

use models\todo\tasks\TaskModel;
use models\todo\tasks\TagsModel;
use models\todo\category\CategoryModel;
use models\Check;

class TaskController
{
    private $check;
    private $tagsModel;
    public function __construct(){
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new Check($userRole);
        $this->tagsModel = new TagsModel();
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
        exit();
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

        $tags = $this->tagsModel->getTagsByTaskId($task['id']);

        include 'app/views/todo/tasks/edit.php';
    }

    public function update()
    {
        $this->check->requirePermission();

        if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['category_id']) && isset($_POST['finish_date'])) {
            $data['id'] = trim($_POST['id']);
            $data['title'] = trim($_POST['title']);
            $data['category_id'] = trim($_POST['category_id']);
            $data['finish_date'] = trim($_POST['finish_date']);
            $data['reminder_at'] = trim($_POST['reminder_at']);
            $data['status'] = trim($_POST['status']);
            $data['priority'] = trim($_POST['priority']);
            $data['description'] = trim($_POST['description']);
            $data['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

            $finish_date_value = $data['finish_date'];
            $reminder_at_option = $data['reminder_at'];
            $finish_date = new \DateTime($finish_date_value);

            switch ($reminder_at_option) {
                case '30_minutes':
                    $interval = new \DateInterval('PT30M');
                    break;
                case '1_hour':
                    $interval = new \DateInterval('PT1H');
                    break;
                case '2_hours':
                    $interval = new \DateInterval('PT2H');
                    break;
                case '12_hours':
                    $interval = new \DateInterval('PT12H');
                    break;
                case '24_hours':
                    $interval = new \DateInterval('P1D');
                    break;
                case '7_days':
                    $interval = new \DateInterval('P7D');
                    break;
            }
            if($interval){
                $reminder_at = $finish_date->sub($interval);
                $data['reminder_at'] = $reminder_at->format('Y-m-d\TH:i');
            }

            //updating data on the task in database
            $taskModel = new TaskModel();
            $taskModel->updateTask($data);

            //tag processing
            $tags = explode(',', $_POST['tags']);
            $tags = array_map('trim', $tags);

            //getting tags on the task when to edit
            $oldTags = $this->tagsModel->getTagsByTaskId($data['id']);

            //deleting old connection between tags and tasks
            $this->tagsModel->removeAllTaskTags($data['id']);

            //adding new tags and connect with a task
            foreach ($tags as $tag_name){
                $tag = $this->tagsModel->getTagByNameAndUserId($tag_name, $data['user_id']);

                if(!$tag){
                    $tag_id = $this->tagsModel->addTag($tag_name, $data['user_id']);
                }else {
                    $tag_id = $tag['id'];
                }

                $this->tagsModel->addTaskTag($data['id'], $tag_id);
            }
            //delete unused tags
            foreach($oldTags as $oldTag){
                $this->tagsModel->removeUnusedTag($oldTag['id']);
            }


        }

        header("Location: /todo/tasks");
    }


    public function delete($params)
    {
        $this->check->requirePermission();

        $todoCategoryModel = new CategoryModel();
        $todoCategoryModel->deleteCategory($params['id']);

        header("Location: /todo/category");
    }
}

