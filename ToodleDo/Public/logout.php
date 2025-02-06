<?php

session_start();
session_destroy();
session_start();
header("Location: login.php");

// Feedback
$_SESSION['userFeedback'] = [
    'message' => 'You have successfully logged out. See you next time!  :)',
    'icon' => 'information'
];