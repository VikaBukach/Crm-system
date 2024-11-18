<?php

namespace controllers\todo\tasks;

use models\todo\tasks\TaskModel;
use models\todo\tasks\TagsModel;
use models\todo\category\CategoryModel;
use models\Check;
use DateTime;
use DateTimeZone;

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
//        $this->check->requirePermission();
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        $taskModel = new TaskModel();
        $tasks = $taskModel->getAllTasksByIdUser($user_id);

        $categoryModel = new CategoryModel();

        //get tags list for each other recording in array
        foreach($tasks as &$task) {
            $task['tags'] = $this->tagsModel->getTagsByTaskId($task['id']);
            $task['category'] = $categoryModel->getCategoryById($task['category_id']);
        }

        include 'app1/views/todo/tasks/index.php';
    }
    public function completed()
    {
//        $this->check->requirePermission();
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        $taskModel = new TaskModel();
        $completedTasks = $taskModel->getAllCompletedTasksByIdUser($user_id);

        $categoryModel = new CategoryModel();

        //get tags list for each other recording in array
        foreach($completedTasks as &$task) {
            $task['tags'] = $this->tagsModel->getTagsByTaskId($task['id']);
            $task['category'] = $categoryModel->getCategoryById($task['category_id']);
        }

        include 'app1/views/todo/tasks/completed.php';
    }

    public function expired()
    {
//        $this->check->requirePermission();
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        $taskModel = new TaskModel();
        $expiredTasks = $taskModel->getAllExpiredTasksByIdUser($user_id);

        $categoryModel = new CategoryModel();

        //get tags list for each other recording in array
        foreach($expiredTasks as &$task) {
            $task['tags'] = $this->tagsModel->getTagsByTaskId($task['id']);
            $task['category'] = $categoryModel->getCategoryById($task['category_id']);
        }
        include 'app1/views/todo/tasks/expired.php';
    }

    public function create()
    {
//        $this->check->requirePermission();

        $todoCategoryModel = new CategoryModel();
        $categories = $todoCategoryModel->getAllCategoriesWithUsability();

        include 'app1/views/todo/tasks/create.php';
    }

    public function store()
    {
//        $this->check->requirePermission();

        if (isset($_POST['title']) && isset($_POST['category_id']) && isset($_POST['finish_date'])) {

            $data['title'] = trim(htmlspecialchars($_POST['title']));
            $data['category_id'] = trim(htmlspecialchars($_POST['category_id']));

            $data['finish_date'] = trim(htmlspecialchars($_POST['finish_date']));
            $date = new DateTime($data['finish_date'], new DateTimeZone('+3'));//Change time UTC:
            $date->setTimezone(new DateTimeZone('UTC'));
            $data['finish_date'] = $date->format('Y-m-d H:i:s');

            $data['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            $data['status'] = 'new';
            $data['priority'] = 'low';
            $data['reminder_at'] = date('Y-m-d H:i:s', strtotime($data['finish_date']));

            $taskModel = new TaskModel();
            $taskModel->createTask($data);
        }

        header("Location: /todo/tasks");
        exit();
    }

    public function edit($params)
    {
//        $this->check->requirePermission();
        $taskModel = new TaskModel();
        $task = $taskModel->getTaskById($params['id']);

        $task_id = isset($params['id']) ? intval($params['id']) : 0;
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        if(!$task || $task['user_id'] != $user_id) {
            http_response_code(404);
            include 'app1/views/errors/404.php';
        }

        $todoCategoryModel = new CategoryModel();
        $categories = $todoCategoryModel->getAllCategoriesWithUsability();

        if (!$task) {
            echo "Task not found";
            return;
        }

        $tags = $this->tagsModel->getTagsByTaskId($task['id']);

        include 'app1/views/todo/tasks/edit.blade.php';
    }

    public function update()
    {
//        $this->check->requirePermission();

        if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['category_id']) && isset($_POST['finish_date'])) {
            $data['id'] = trim($_POST['id']);
            $data['title'] = trim(htmlspecialchars($_POST['title']));
            $data['category_id'] = trim(htmlspecialchars($_POST['category_id']));
            $data['finish_date'] = trim(htmlspecialchars($_POST['finish_date']));
            $data['reminder_at'] = trim(htmlspecialchars($_POST['reminder_at']));
            $data['status'] = trim(htmlspecialchars($_POST['status']));
            $data['priority'] = trim(htmlspecialchars($_POST['priority']));
            $data['description'] = trim(htmlspecialchars($_POST['description']));
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
//        $this->check->requirePermission();

        $todoCategoryModel = new CategoryModel();
        $todoCategoryModel->deleteCategory($params['id']);

        header("Location: /todo/tasks");
    }

public function tasksByTag($params)
{
//    $this->check->requirePermission();

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    $taskModel = new TaskModel();
    $tasksByTag = $taskModel->getTasksByTagId($params['id'], $user_id);

    $tagsModel = new TagsModel();
    $tagname = $tagsModel->getTagNameById($params['id']);


    $categoryModel = new CategoryModel();

   //get tags list for each other recording in array
    foreach($tasksByTag as &$task) {
        $task['tags'] = $this->tagsModel->getTagsByTaskId($task['task_id']);
        $task['category'] = $categoryModel->getCategoryById($task['category_id']);
    }

    include 'app1/views/todo/tasks/by-tag.php';
}
    public function updateStatus($params)
    {
//        $this->check->requirePermission();

        $datetime = null;
        $status = trim(htmlspecialchars($_POST['status']));
        if($_POST['status']) {
            if($_POST['status'] === 'completed_at') {
                $datetime = date("Y-m-d H:i:s");
            }
            $taskModel = new TaskModel();
            $taskModel-> updateTaskStatus($params['id'], $status, $datetime);

            header("Location: /todo/tasks");
        }else{
            echo "Status not updated";
        }
    }
    public function task($params)
    {
//        $this->check->requirePermission();

        $task_id = isset($params['id']) ? intval($params['id']) : 0;
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        $taskModel = new TaskModel();
        $task = $taskModel->getTaskByIdAndByIdUser($params['id'], $user_id);

        if(!$task || $task['user_id'] != $user_id) {
            http_response_code(404);
            include 'app1/views/errors/404.php';
        }

        $todoCategoryModel = new CategoryModel();
        $category = $todoCategoryModel->getCategoryById($task['category_id']);

        if (!$task) {
            echo "Task not found";
            return;
        }
        $tags = $this->tagsModel->getTagsByTaskId($task['id']);

        include 'app1/views/todo/tasks/task.php';
    }
}


