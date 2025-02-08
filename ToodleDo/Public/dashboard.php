<?php

// SS
session_start();
require './../Includes/task.php';


// sends logged users to dashboard; if not authorised
$taskDB->pdo->sendAway();






// Get all sorts of tasks/notifications
$completed = $taskDB->selectCompleted($_SESSION['id']);
$inProgress = $taskDB->selectInProgressPending($_SESSION['id']);
$overDueTasks = $taskDB->selectOverdueTasks($_SESSION['id']);
$recent = $taskDB->selectRecentTasks($_SESSION['id']);

// sets overdue tasks to overdue
$taskDB->changeOverDue($_SESSION['id']);


// Form handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // handling new task form
    if (isset($_POST['addtsk'])) {
        try {
            // task insertion
            $taskDB->addTask(
                $_SESSION['id'],
                $_POST['tskTitle'],
                $_POST['tskDescription'],
                $_POST['startDate'],
                $_POST['dueDate'],
                $_POST['status']
            );

            // Feedback
            $taskDB->pdo->feedback("Task Added Successfully", "check");

            $taskDB->pdo->pageRef('dashboard.php');
        } catch (\Throwable $th) {
            // Feedback/redirect
            $taskDB->pdo->feedback("Oopsies... Something went wrong, please try again later", "information");
            $taskDB->pdo->pageRef($_SERVER['PHP_SELF']);
        }
    }


    // Handling task update 
    if (isset($_GET['TSKXXX']) && isset($_POST['edittsk'])) {
        try {
            // task editing
            $taskDB->editTask(
                $_GET['TSKXXX'],
                $_SESSION['id'],
                $_POST['tskTitle'],
                $_POST['tskDescription'],
                $_POST['startDate'],
                $_POST['dueDate'],
                $_POST['status']
            );

            // Feedback
            $taskDB->pdo->feedback("Task Updated Successfully", "check");

            // header
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (\Throwable $th) {
            // Feedback/redirect
            $taskDB->pdo->feedback("Oopsies... Something went wrong, please try again later", "information");
            $taskDB->pdo->pageRef($_SERVER['PHP_SELF']);
        }
    }
}



// Handling task deletion GET request
if (isset($_GET['TSKDLXXX'])) {
    try {
        // task deletion
        $taskDB->deleteTask($_GET['TSKDLXXX'], $_SESSION['id']);
        // Feedback/redirect
        $taskDB->pdo->feedback("Task Deleted Successfully", "check");
        $taskDB->pdo->pageRef($_SERVER['PHP_SELF']);
    } catch (\Throwable $th) {
        // Feedback/redirect
        $taskDB->pdo->feedback("Oopsies... Something went wrong, please try again later", "information");
        $taskDB->pdo->pageRef($_SERVER['PHP_SELF']);
    }
}


// Filling update form placeholders
if (isset($_GET['TSKXXX'])) {
    try {
        $tskData = $taskDB->selectTaskById($_GET['TSKXXX'], $_SESSION['id']);
        $status = $tskData['status'];
    } catch (PDOException $e) {
        // echo $e->getMessage();
        // Feedback/redirect
        $taskDB->pdo->feedback("Oopsies... Something went wrong, please try again later", "information");
        $taskDB->pdo->pageRef($_SERVER['PHP_SELF']);
    }
}





?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToodleDo</title>
    <link rel="icon" href="./../Assets/Icons/todoodle.png" type="image/gif">
    <link rel="stylesheet" href="./../Css/body.css">
    <link rel="stylesheet" href="./../Css/toast.css">
    <link rel="stylesheet" href="./../Css/navbar.css">
    <link rel="stylesheet" href="./../Css/dashboard.css">
    <script src="./../Js/nav.js" defer></script>
    <script src="./../Js/taskForm.js" defer></script>
    <script src="./../Js/notifications.js" defer></script>
    <script type="text/javascript" src="./../Js/toastFeedback.js" defer></script>
    <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>


<body>
    <div class="backgr"></div>
    <button class="sidebar-menu-button">
        <span class="material-symbols-rounded">menu</span>
    </button>
    <aside class="sidebar collapsed">
        <header class="sidebar-header">
            <a href="/" class="header-logo">
                <img src="./../Assets/Icons/todoodle.png" alt="Toodledo" />
            </a>
            <button class="sidebar-toggler">
                <span class="material-symbols-rounded">chevron_left</span>
            </button>
        </header>
        <nav class="sidebar-nav">
            <ul class="nav-list primary-nav">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <span class="material-symbols-rounded">dashboard</span>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>
                <!-- Dropdown for Tasks -->
                <li class="nav-item dropdown-container">
                    <a href="#" class="nav-link dropdown-toggle">
                        <span class="material-symbols-rounded">task</span>
                        <span class="nav-label">Tasks</span>
                        <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                    </a>
                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu">
                        <li class="nav-item"><a href="taskManager.php" class="nav-link dropdown-link">All Tasks</a></li>
                        <li class="nav-item formSlider"><a class="nav-link dropdown-link">New Task</a></li>
                        <li class="nav-item"><a href="taskArchive.php" class="nav-link dropdown-link">Completed</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link notifViewBtn">
                        <span class="material-symbols-rounded">notifications</span>
                        <span class="nav-label">Notifications</span>
                    </a>
                </li>
            </ul>
            <!-- Secondary Bottom Nav -->
            <ul class="nav-list secondary-nav">
                <li class="nav-item">
                    <a href="profile.php" class="nav-link">
                        <span class="material-symbols-rounded">person</span>
                        <span class="nav-label">Profile Manager</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <span class="material-symbols-rounded">logout</span>
                        <span class="nav-label">Sign Out</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    <div class="tskContainer">
        <div class="welcome">
            <div class="wleft">
                <h2>Hello, <?php echo htmlspecialchars(ucfirst(strtolower($_SESSION['username']))); ?></h2>
                <span>Today is <?php echo date('l, j F Y'); ?></span>
            </div>
            <div class="wright">
                <button type="button" class="tskBTN formSlider">
                    <span class="tskBTN__text">Add Task</span>
                    <span class="tskBTN__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none"
                            class="svg">
                            <line y2="19" y1="5" x2="12" x1="12"></line>
                            <line y2="12" y1="12" x2="19" x1="5"></line>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
        <div class="taskFullOverview">
            <div class="tskOverview">
                <div class="tskHeader">
                    <h3>Task Overview</h3>
                </div>
                <div class="tskS">
                    <div class="task-card upcoming">
                        <?php if (isset($inProgress) && $inProgress): ?>
                            <h3>📅 Upcoming Tasks</h3>
                            <ul class="task-list">
                                <?php foreach ($inProgress as $Ptsk): ?>
                                    <li>
                                        <?= htmlspecialchars($Ptsk['title']) ?>
                                        <span class="due-date">Due:
                                            <?= htmlspecialchars($Ptsk['due_date']) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>📅 Your Upcoming/Pending Tasks Will Appear Here</p>
                        <?php endif; ?>
                    </div>
                    <div class="task-card completed">
                        <?php if (isset($completed) && $completed): ?>
                            <h3>✅ Completed Tasks</h3>
                            <ul class="task-list">
                                <?php foreach ($completed as $tskC): ?>
                                    <li>
                                        <?= htmlspecialchars($tskC['title']) ?>
                                        <span class="due-date">completed on:
                                            <?= htmlspecialchars($tskC['up_date']) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>✅ Your Completed Tasks Will Appear Here</p>
                        <?php endif; ?>
                    </div>
                    <div class="task-card overdue">
                        <?php if (isset($overDueTasks) && $overDueTasks): ?>
                            <h3>🚨 Overdue Tasks</h3>
                            <span class="undeTitleSpan">Overdue tasks will be cleared out after 3 days of its
                                expiration</span>
                            <br>
                            <ul class="task-list">
                                <?php foreach ($overDueTasks as $Otsk): ?>
                                    <li>
                                        <?= htmlspecialchars($Otsk['title']) ?>
                                        <span class="due-date">was due:
                                            <?= htmlspecialchars($Otsk['due']) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>🚨 Your Overdue Tasks Will Appear Here</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <div class="tskCurrent">
                <div class="tskHeader">
                    <h3>Current Tasks</h3>
                </div>
                <div class="tskBox">
                    <?php if (isset($recent) && $recent): ?>
                        <ul>
                            <?php foreach ($recent as $rTSK): ?>
                                <li>
                                    <span class="taskTitle">🔹<?= htmlspecialchars($rTSK['title']) ?></span>

                                    <?php if ($rTSK['status'] == 'overdue'): ?>
                                        <span class="taskDueDate">was due: <?= htmlspecialchars($rTSK['due']) ?></span>
                                        <span
                                            class="taskStatus <?= htmlspecialchars($rTSK['status']) ?>"><?= htmlspecialchars($rTSK['status']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="taskDueDate">Due: <?= htmlspecialchars($rTSK['due']) ?></span>
                                        <span
                                            class="taskStatus <?= htmlspecialchars($rTSK['status']) ?>"><?= htmlspecialchars($rTSK['status']) ?>
                                        </span>
                                        <div class="taskActions">
                                            <a href="?TSKXXX=<?= htmlspecialchars($rTSK['id']) ?>"><button
                                                    class="editBtn">Edit</button></a>
                                            <a href="?TSKDLXXX=<?= htmlspecialchars($rTSK['id']) ?>"><button class="deleteBtn"
                                                    name="TSKDLXXXBTN">Delete</button></a>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>📅 Your Recent Tasks Will Appear Here</p>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="gCalendar">
                    <iframe
                        src="https://calendar.google.com/calendar/embed?src=nl.dutch%23holiday%40group.v.calendar.google.com&ctz=Europe%2FAmsterdam"
                        style="border: 0" frameborder="0" scrolling="no"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="frmContainer">
        <div class="modal">
            <div class="modal__header">
                <span class="modal__title">New Task 📝</span>
                <button class="frmBTN frmBTN--icon formCloserBTN">
                    <svg width="24" viewBox="0 0 24 24" height="24" xmlns="http://www.w3.org/2000/svg">
                        <path fill="none" d="M0 0h24v24H0V0z"></path>
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z">
                        </path>
                    </svg>
                </button>
            </div>
            <form method="POST">
                <div class="modal__body">
                    <div class="input">
                        <label class="input__label">Task title</label>
                        <input class="input__field" type="text" name="tskTitle" placeholder="Go to gym" required>
                        <p class="input__description">Try something short and descriptive</p>
                    </div>
                    <div class="input">
                        <label class="input__label">Start-Date</label>
                        <input class="input__field" min="<?= date('Y-m-d\TH:i') ?>" type="datetime-local"
                            name="startDate" required>
                    </div>
                    <div class="input">
                        <label class="input__label">Deadline</label>
                        <input class="input__field" min="<?= date('Y-m-d\TH:i') ?>" type="datetime-local" name="dueDate"
                            required>
                        <p class="input__description">Tip: set realistic deadlines :P</p>
                    </div>
                    <div class="input">
                        <label class="input__label">Status</label>
                        <select class="input__field" name="status" id="status" required>
                            <option value="pending">🕒Pending</option>
                            <option value="completed">✅ Completed</option>
                            <option value="in-progress" selected>🚧 In-progress</option>
                        </select>
                    </div>
                    <div class="input">
                        <label class="input__label">Description</label>
                        <textarea rows="4" cols="50" class="input__field input__field--textarea" name="tskDescription"
                            placeholder="Time to sweat it out! Get to the gym and crush your workout."
                            required></textarea>
                        <p class="input__description">Give your task a good description</p>
                    </div>
                </div>
                <div class="modal__footer">
                    <button type="submit" class="frmBTN frmBTN--primary" name="addtsk">Create Task</button>
                </div>
            </form>
        </div>
    </div>

    <div class="notifsView">
        <div class="notifWrapper">
            <div class="notifsTitle">
                <h3>Notifications Centre</h3>
                <button class="notifsCloserBtn frmBTN--icon">
                    <svg width="24" viewBox="0 0 24 24" height="24" xmlns="http://www.w3.org/2000/svg">
                        <path fill="none" d="M0 0h24v24H0V0z"></path>
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z">
                        </path>
                    </svg>
                </button>
            </div><?php if (isset($_GET['TDLNTF'])): ?> <!--Continue here -->
                <div class="notifsBox">
                    <div class="notif">
                        <h3>Overdue tasks</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit, earum exercitationem ex ea
                            aliquam corporis totam placeat mollitia, excepturi, esse quas ipsam reprehenderit eaque dolorum?
                            Labore velit cumque delectus quia.</p>
                    </div>
                    <div class="notif">
                        <h3>Overdue tasks</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit, earum exercitationem ex ea
                            aliquam corporis totam placeat mollitia, excepturi, esse quas ipsam reprehenderit eaque dolorum?
                            Labore velit cumque delectus quia.</p>
                    </div>
                </div>
            <?php else: ?>
                <h1 class="noNotifs">No notifications for now, time to focus on tasks 😊</h1>
            <?php endif; ?>

        </div>
    </div>

    <?php if (isset($_GET['TSKXXX']) && isset($tskData)): ?>
        <div class="frmEDIT">
            <div class="modal">
                <div class="modal__header">
                    <span class="modal__title">Edit Task 📝</span>
                    <button class="frmBTN frmBTN--icon formCloserBTN">
                        <svg width="24" viewBox="0 0 24 24" height="24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="none" d="M0 0h24v24H0V0z"></path>
                            <path
                                d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z">
                            </path>
                        </svg>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal__body">
                        <div class="input">
                            <label class="input__label">New title</label>
                            <input class="input__field" type="text" name="tskTitle" placeholder="Go to gym"
                                value="<?= htmlspecialchars($tskData['title']) ?>" required>
                            <p class="input__description">Try something short and descriptive</p>
                        </div>
                        <div class="input">
                            <label class="input__label">New Start-Date</label>
                            <input class="input__field" min="<?= date('Y-m-d\TH:i') ?>" type="datetime-local"
                                name="startDate" value="<?= htmlspecialchars($tskData['start_date']) ?>" required>
                        </div>
                        <div class="input">
                            <label class="input__label">New Deadline</label>
                            <input class="input__field" min="<?= date('Y-m-d\TH:i') ?>" type="datetime-local" name="dueDate"
                                value="<?= htmlspecialchars($tskData['due_date']) ?>" required>
                            <p class="input__description">Tip: set realistic deadlines :P</p>
                        </div>
                        <div class="input">
                            <label class="input__label">Status?! :P</label>
                            <select class="input__field" name="status" required id="status">
                                <option value="" disabled <?= empty($status) ? 'selected' : '' ?>>Please select task status
                                </option>
                                <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>🕒Pending</option>
                                <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>✅ Completed</option>
                                <option value="in-progress" <?= $status == 'in-progress' ? 'selected' : '' ?>>🚧 In-progress
                                </option>
                            </select>
                        </div>
                        <div class="input">
                            <label class="input__label">New Description</label>
                            <textarea rows="4" cols="50" class="input__field input__field--textarea"
                                name="tskDescription"><?= htmlspecialchars($tskData['description']) ?></textarea>
                            <p class="input__description">Give your task a good description</p>
                        </div>
                    </div>
                    <div class="modal__footer">
                        <button type="submit" class="frmBTN frmBTN--primary" name="edittsk">Edit Task</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Fancy Feedback  -->
    <div id="toastbox uflp">
        <?php if (isset($_SESSION['userFeedback'])): ?>
            <!-- // Display toast FROM PHP // -->
            <div class="toast uflp">
                <i>
                    <img src="./../Assets/Icons/<?= htmlspecialchars($_SESSION['userFeedback']['icon']) ?>.png" alt="logo">
                </i>
                <p>
                    <?= htmlspecialchars($_SESSION['userFeedback']['message']) ?>
                </p>
            </div>
        </div>
        <?php unset($_SESSION['userFeedback']); endif; ?>
    <script src="./../Js/loader.js"></script>
</body>

</html>