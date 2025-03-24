<?php

// SS/DB
require_once './../Includes/user.php';
session_start();


// Sends logged users to dashboard
$userDB->pdo->sendToDashboard();


// Handles form request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get user data
    $user = $userDB->logUser($_POST['email']);

    if ($user) {
        // Verify password
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['logged'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            if (isset($user['profile_pic_path'])) {
                $_SESSION['profile_path'] = $user['profile_pic_path'];
            }
            $userDB->pdo->pageRef('dashboard.php');
        }
    }
    $userDB->pdo->feedback("We couldn't log you in. Please check your credentials and try again.", "cross");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toodledo Login</title>
    <link rel="icon" href="./../Assets/Icons/todoodle.png" type="image/gif">
    <link rel="stylesheet" href="./../Css/body.css">
    <link rel="stylesheet" href="./../Css/relog.css">
    <link rel="stylesheet" href="./../Css/toast.css">
    <script type="text/javascript" src="./../Js/toastFeedback.js" defer></script>
    <script type="text/javascript" src="./../Js/validations.js" defer></script>
</head>

<body>
    <div class="wrapper">
        <div class="toodleDo">
            <img src="./../Assets/Icons/todoodle.png" alt="logo">
            <span class="toDoTitle">ToodleDo</span>
        </div>
        <h1>Login</h1>
        <form method="post" id="form">
            <div>
                <label for="email-input">
                    <span class="emLBL">@</span>
                </label>
                <input type="email" name="email" id="email-input" placeholder="Email">
            </div>
            <div>
                <label for="password-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="undefined">
                        <path
                            d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z" />
                    </svg>
                </label>
                <input type="password" name="password" id="password-input" placeholder="Password">
            </div>
            <button type="submit">Login</button>
        </form>
        <p>New here? <a href="sign_up.php">Create an account</a></p>
    </div>
    <!-- Fancy Feedback  -->
    <div id="toastbox">
        <!-- // Display toast FROM PHP // -->
        <?php if (isset($_SESSION['userFeedback'])): ?>
            <div class="toast">
                <i>
                    <img src="./../Assets/Icons/<?= htmlspecialchars($_SESSION['userFeedback']['icon']) ?>.png" alt="logo">
                </i>
                <p>
                    <?= htmlspecialchars($_SESSION['userFeedback']['message']) ?>
                </p>
            </div>
            <?php unset($_SESSION['userFeedback']); endif; ?>
    </div>
    <script src="./../Js/loader.js"></script>
</body>

</html>