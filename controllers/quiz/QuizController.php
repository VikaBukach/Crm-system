<?php

namespace controllers\quiz;

use models\quiz\QuizModel;
use models\roles\Role;
use models\Check;

class QuizController
{
    private $check;
    public function __construct(){
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new Check($userRole);
    }

    public function index()
    {
//        $this->check->requirePermission();

        $quizModel = new QuizModel();
        $quizes = $quizModel->readAll();

        include 'app/views/quiz/index.php';
    }

    public function create()
    {
//        $this->check->requirePermission();

        include 'app/views/quiz/create.php';
    }

    public function store()
    {
//        $this->check->requirePermission();

        if (isset($_POST['question']) && isset($_POST['answer_1']) && isset($_POST['answer_2']) && isset($_POST['answer_3'])&& isset($_POST['correct_answer'])) {
            $data['question'] = trim(htmlspecialchars($_POST['question']));
            $data['answer_1'] = trim(htmlspecialchars($_POST['answer_1']));
            $data['answer_2'] = trim(htmlspecialchars($_POST['answer_2']));
            $data['answer_3'] = trim(htmlspecialchars($_POST['answer_3']));
            $data['correct_answer'] = trim(htmlspecialchars($_POST['correct_answer']));
            $data['explanation'] = trim(htmlspecialchars($_POST['explanation'])) ? trim(htmlspecialchars($_POST['explanation'])) : '';

            $quizModel = new QuizModel();
            $quizModel->createQuiz($data);
        }

        header("Location: /quiz");
    }

    public function edit($params)
    {
        $this->check->requirePermission();

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
        $this->check->requirePermission();

        if (isset($params['id']) && isset($_POST['title']) && isset($_POST['slug']) && isset($_POST['roles']) ) {
            $id = trim($params['id']);
            $title = trim(htmlspecialchars($_POST['title']));
            $slug = trim(htmlspecialchars($_POST['slug']));
            $roles = filter_var_array($_POST['roles'], FILTER_SANITIZE_NUMBER_INT);
            $roles = implode("," ,$roles);

            if (empty($title) || empty($slug) || empty($roles)) {
                echo "Title, Slug and Roles fields are required";
                return;
            }

            $pageModel = new PageModel();
            $pageModel->updatePage($id, $title, $slug, $roles);
        }
        header("Location: /pages");
    }

    public function delete($params)
    {
        $this->check->requirePermission();

        $pageModel = new PageModel();
        $pageModel->deletePage($params['id']);

        header("Location: /pages");
    }
}



