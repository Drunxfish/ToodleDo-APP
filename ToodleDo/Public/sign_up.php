<?php

// SS/DB
require_once './../Includes/user.php';
session_start();


// sends logged users to dashboard
$userDB->pdo->sendToDashboard();


// handles form request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // get email
    if ($userDB->selectEmail($_POST['email'])) {
        # feedback/redirect
        $userDB->pdo->feedback("This email address is unavailable. Please choose a different one or log in to your account.", "information");
        $userDB->pdo->pageRef($_SERVER['PHP_SELF']);
    }
    
    // email validation (extra)
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        # feedback/redirect
        $userDB->pdo->feedback("Invalid email address. Please enter a valid email address.", "cross");
        $userDB->pdo->pageRef($_SERVER['PHP_SELF']);
    }


    // try to submitting form
    try {
        $userDB->signupUser(
            $_POST['username'],
            $_POST['email'],
            $_POST['password']
        );
        # feedback/redirect
        $userDB->pdo->feedback("Registration successful! Your account has been created.", "check");
        $userDB->pdo->pageRef('login.php');

    } catch (PDOException $e) {
        # feedback
        $userDB->pdo->feedback("Registration Failed, Please try again later.", "information");
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toodledo Signup</title>
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
        <h1>Start Organizing</h1>
        <form method="post" id="form">
            <div>
                <label for="username-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="undefined">
                        <path
                            d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-240v-32q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v32q0 33-23.5 56.5T720-160H240q-33 0-56.5-23.5T160-240Z" />
                    </svg>
                </label>
                <input type="text" name="username" id="username-input" placeholder="Username">
            </div>
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
            <div>
                <label for="repeat-password-input">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="undefined">
                        <path
                            d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z" />
                    </svg>
                </label>
                <input type="password" name="repeat-password" id="repeat-password-input" placeholder="Repeat Password">
            </div>
            <button type="submit">Signup</button>
        </form>
        <p>Already have an Account? <a href="login.php">login</a></p>
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