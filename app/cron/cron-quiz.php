<?php

session_start();

require_once '../../local-config.php';
require_once '../../autoload.php';

use models\Database;
use models\telegram\TelegramBot;
use models\quiz\QuizModel;

    $db = Database::getInstance()->getConnection();

    try{
        // doing random selection of one question
    $quizModel = new QuizModel();
    $quiz = null;
        // checking repeat question if it was for 10 days
        $amountOfTime = date('Y-m-d H:i:s', strtotime('-10 days'));

        while(!$quiz) {
            $randomQuiz = $quizModel->getRandomQuiz(); //get question
            $count = $quizModel->checkTelegramQuizQuestion($randomQuiz['id'], $amountOfTime);

            if($count == 0){
                $quiz = $randomQuiz;
            }
        }

    $answers = [$quiz['answer_1'], $quiz['answer_2'], $quiz['answer_3']];

    $dataSet = [
        'chat_id' => TELEGRAM_CHAT_ID,
        'question' => $quiz['question'],
        'options' => $answers,
        'is_anonymous' => true,
        'allows_multiple_answers' => false,
        'correct_option_id' => $quiz['correct_answer'],
        'explanation' => $quiz['explanation']
    ];

    $telegramBot = new TelegramBot(TELEGRAM_BOT_API_KEY);
    $telegramBot->sendTelegramQuizMessage($dataSet);

    $quizModel->writeInTelegramQuizQuestions($quiz['id']);

    }catch(\PDOException $e){
    echo "Error:" . $e->getMessage();
    }





