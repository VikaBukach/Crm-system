<?php


//if($_SERVER['REQUEST_URI'] == '/crm_for_telegramBot/index.php') {
//    header('Location: crm_for_telegramBot/');
//    exit();
//}

$title = 'Home page';
ob_start();
?>
<h1>Home</h1>

<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>

