<?php

session_start();

require_once '../../config.php';
require_once '../../autoload.php';

use models\Database;
use models\telegram\TelegramBot;
use models\quiz\QuizModel;

$db = Database::getInstance()->getConnection();
//random selection of one question
$quizModel = new QuizModel();
$quiz = $quizModel->getRandomQuiz();


$answers = [$quiz['answer_1'], $quiz['answer_2'], $quiz['answer_3']];

$dataSet = [
    'chat_id' => TELEGRAM_CHAT_ID,
    'question' => $quiz['question'],
    'options' => $answers,
    'is_anonymous' => True, // true & false
    'allows_multiple_answers' => False, // true & false
    'correct_option_id' => $quiz['correct_answer'],
    'explanation' => $quiz['explanation']
];

//tte($dataSet);

$telegramBot = new TelegramBot(TELEGRAM_BOT_API_KEY);
$telegramBot->sendTelegramMessage($dataSet);

tte($quiz);
    try{

}catch(\PDOException $e){
    echo "Error:" . $e->getMessage();
}





