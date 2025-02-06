<?php

session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['logged'])) {
    header('Location: login.php');
    exit;
}


session_destroy();
session_start();


// Feedback
$_SESSION['userFeedback'] = [
    'message' => 'You have successfully logged out. See you next time!  :)',
    'icon' => 'information'
];

header("Location: login.php");
exit;