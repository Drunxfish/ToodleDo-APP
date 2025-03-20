<?php

// SS
session_start();
require './../Includes/task.php';

// sends logged users to dashboard; if not authorised
$taskDB->pdo->sendAway();

$notifs = $taskDB->selectNotifications($_SESSION['id']);
$wlcm = $taskDB->greetUser($_SESSION['username']);

// Get all sorts of tasks/notifications
$allTasks = $taskDB->selectAll($_SESSION['id']);
$completed = $taskDB->selectCompleted($_SESSION['id']);
$inProgress = $taskDB->selectInProgressPending($_SESSION['id']);
$overDueTasks = $taskDB->selectOverdueTasks($_SESSION['id']);
$recent = $taskDB->selectRecentTasks($_SESSION['id']);



// Clearing overdue tasks(order matters)
$taskDB->deleteOverdue($_SESSION['id']); // DELETES overdue tasks 
$taskDB->changeOverDue($_SESSION['id']); // Changes status of overdue tasks
$taskDB->changeInprogress($_SESSION['id']); // Changes status of pending tasks to in-progress




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
                'pending'
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
            $taskDB->pdo->feedback("Task Updated Successfully", "information");

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
    } catch (\Throwable $th) {
        // Feedback/redirect
        $taskDB->pdo->feedback("Oopsies... Something went wrong, please try again later", "information");
        $taskDB->pdo->pageRef($_SERVER['PHP_SELF']);
    }
}

// Handling task status change
if (isset($_GET['TSKIDXXXXXXXXXXXXXXXXXXXXXXXXXX']) && isset($_GET['TSKSTSXXXXXXXXXXXXXXXXXXXXXXXXXX'])) {

    $tskIDDD = $_GET['TSKIDXXXXXXXXXXXXXXXXXXXXXXXXXX'];
    $tskSTSTS = $_GET['TSKSTSXXXXXXXXXXXXXXXXXXXXXXXXXX'];

    switch ($tskSTSTS) {
        case 'delete':
            $taskDB->deleteTask($tskIDDD, $_SESSION['id']);
            break;
        case 'in-progress':
        case 'pending':
        case 'completed':
            $taskDB->changeTaskStatus($_SESSION['id'], $tskIDDD, $tskSTSTS);
            break;
        default:
            break;
    }
}


// Filling update form values
if (isset($_GET['TSKXXX']) && $_GET['TSKXXX']) {
    try {
        $tskData = $taskDB->selectTaskById($_GET['TSKXXX'], $_SESSION['id']);

        if (!$tskData) {
            throw new Exception("Task couldn't be retrieved");
        }
        $status = $tskData['status'];
    } catch (Exception $e) {
        // echo $e->getMessage();
        // Feedback/redirect
        $taskDB->pdo->feedback("Oopsies... Something went wrong, please try again later", "information");
        $taskDB->pdo->pageRef($_SERVER['PHP_SELF']);
    }
}

// Handling notification deletion
if (isset($_GET['NTFIDXD'])) {
    try {
        $taskDB->deleteAllNotifications($_SESSION['id']);
        unset($_SESSION['modifiedTSKS']);
    } catch (\Throwable $th) {
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
    <script src="./../Js/getStatus.js" defer></script>
    <script src="./../Js/taskView.js" defer></script>
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
                        <li class="nav-item"><a class="nav-link dropdown-link tskDispTr">All Tasks</a></li>
                        <li class="nav-item formSlider"><a class="nav-link dropdown-link">New Task</a></li>
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
                <h2><?php echo htmlspecialchars(ucfirst(strtolower($wlcm))); ?></h2>
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
                    <span class="undeTitleSpan">✏️ Check descriptions or make edits in</span>
                </div>
                <div class="tskS">
                    <div class="task-card upcoming">
                        <?php if (isset($inProgress) && $inProgress): ?>
                            <h3>📅 Upcoming Tasks</h3>
                            <span class="undeTitleSpan">Pending tasks will automatically change to In-progress when task
                                start date arrives 😎</span>
                            <table class="inProgressTSK-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Due Date</th>
                                        <th>Due Time</th>
                                        <th colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inProgress as $Ptsk): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($Ptsk['title']) ?></td>
                                            <td><?= htmlspecialchars($Ptsk['due_date']) ?></td>
                                            <td><?= htmlspecialchars($Ptsk['due_time']) ?></td>
                                            <td>
                                                <select class="taskStatusSelect <?= htmlspecialchars($Ptsk['status']) ?>"
                                                    TSKIDXXXXXXXXXX="<?= htmlspecialchars($Ptsk['id']) ?>">
                                                    <?php if ($Ptsk['status'] == 'in-progress'): ?>
                                                        <option value="in-progress" selected disabled>In-progress</option>
                                                    <?php else: ?>
                                                        <option value="pending" <?= $Ptsk['status'] == 'pending' ? 'selected disabled' : '' ?>>Pending</option>
                                                    <?php endif; ?>
                                                    <option value="completed" <?= $Ptsk['status'] == 'completed' ? 'selected disabled' : '' ?>>Completed</option>
                                                    <option value="delete">Delete</option>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>📅 Your Upcoming/Pending Tasks Will Appear Here</p>
                        <?php endif; ?>
                    </div>
                    <div class="task-card completed">
                        <?php if (isset($completed) && $completed): ?>
                            <h3>✅ Completed Tasks</h3>
                            <?php if (isset($completed) && $completed): ?>
                                <span class="undeTitleSpan">Completed tasks will be archived 2 days after their
                                    completion.</span>
                            <?php endif; ?>
                            <table class="completedTSK-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($completed as $tskC): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($tskC['title']) ?></td>
                                            <td><?= htmlspecialchars($tskC['up_date']) ?></td>
                                            <td><?= htmlspecialchars($tskC['up_time']) ?></td>
                                            <td><?= htmlspecialchars($tskC['completion_time']) == null || htmlspecialchars($tskC['completion_time']) == 0 ? 'N/A' : htmlspecialchars($tskC['completion_time']) . ' minutes' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
                    <h3>Recent Tasks</h3>
                    <span class="undeTitleSpan">
                        📋 Not seeing it? Browse all tasks: <button class="tskDispTr ovDispBTN">Overview</button>
                    </span>
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
        <form method="post" class="addFRMC">
            <div class="title">
                <span>📝 New Task </span>
                <span class="material-symbols-rounded formCloserBTN">
                    close
                </span>
            </div>
            <div class="frmBody">
                <div class="inpGrp">
                    <span class="material-symbols-rounded">
                        keep
                    </span>
                    <input type="text" name="tskTitle" placeholder="Task name" required>
                </div>
                <div class="inpGrp">
                    <span class="material-symbols-rounded">
                        calendar_month
                    </span>
                    <input class="input__field" min="<?= date('Y-m-d\TH:i') ?>" value="<?= date('Y-m-d\TH:i') ?>"
                        type="datetime-local" name="startDate" required>
                </div>
                <div class="inpGrp">
                    <span class="material-symbols-rounded">
                        event_upcoming
                    </span>
                    <input class="input__field" min="<?= date('Y-m-d\TH:i', strtotime('+1 day')) ?>"
                        value="<?= date('Y-m-d\TH:i', strtotime('+1 day')) ?>" type="datetime-local" name="dueDate"
                        required>
                </div>
                <div class="inpGrp ingrpSpec">
                    <span class="material-symbols-rounded">
                        description
                    </span>
                    <textarea rows="4" cols="50" class="input__field input__field--textarea" name="tskDescription"
                        placeholder="Task description example: Time to sweat it out! Get to the gym and crush your workout."
                        required></textarea>
                </div>
                <div class="inpGrp btnfprio">
                    <button type="submit" class="frmBTN btn" name="addtsk">Create Task</button>
                </div>
            </div>
        </form>
    </div>

    <div class="notifsView">
        <div class="notifWrapper">
            <div class="notifsTitle">
                <div class="ntfspc">
                    <h3>Notifications Centre</h3>
                    <?php if (isset($notifs) && $notifs): ?>
                        <span class="offNTF">
                            <a href="?NTFIDXD">
                                <span>Clear Notifications</span>
                                <span class="material-symbols-rounded deleteNTF">delete</span>
                            </a>
                        </span>
                    <?php endif; ?>
                </div>
                <button class="notifsCloserBtn frmBTN--icon">
                    <span class="material-symbols-rounded">
                        close
                    </span>
                </button>
            </div>
            <!-- display notifications -->
            <?php if (isset($notifs) && $notifs): ?>
                <div class="notifsBox">
                    <?php foreach ($notifs as $notinfo): ?>
                        <div class="notif">
                            <div class="ntfspc">
                                <h3><?= htmlspecialchars($notinfo['title']) ?></h3>
                            </div>
                            <p><?= htmlspecialchars($notinfo['message']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h1 class="noNotifs">No notifications for now, time to focus on tasks 😊</h1>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php if (isset($_GET['TSKXXX']) && $_GET['TSKXXX'] && isset($tskData)): ?>
        <div class="frmEDIT">
            <form method="post" class="editFRMC ">
                <div class="title">
                    <span>📝 Edit Task </span>
                    <span class="material-symbols-rounded formCloserBTN">
                        close
                    </span>
                </div>
                <div class="frmBody">
                    <div class="inpGrp">
                        <span class="material-symbols-rounded">
                            keep
                        </span>
                        <input class="input__field" type="text" name="tskTitle" placeholder="Go to gym"
                            value="<?= htmlspecialchars($tskData['title']) ?>" required>
                    </div>
                    <div class="inpGrp">
                        <span class="material-symbols-rounded">
                            calendar_month
                        </span>
                        <input class="input__field" min="<?= date('Y-m-d\TH:i') ?>" type="datetime-local" name="startDate"
                            value="<?= htmlspecialchars($tskData['start_date']) ?>" required>
                    </div>
                    <div class="inpGrp">
                        <span class="material-symbols-rounded">
                            event_upcoming
                        </span>
                        <input class="input__field" min="<?= date('Y-m-d\TH:i') ?>" type="datetime-local" name="dueDate"
                            value="<?= htmlspecialchars($tskData['due_date']) ?>" required>
                    </div>
                    <div class="inpGrp">
                        <span class="material-symbols-rounded">
                            pending_actions
                        </span>
                        <select name="status" required id="status">
                            <option value="" disabled <?= empty($status) ? 'selected' : '' ?>>Please select task
                                status
                            </option>
                            <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>🕒Pending</option>
                            <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>✅ Completed
                            </option>
                            <option value="in-progress" <?= $status == 'in-progress' ? 'selected' : '' ?>>🚧
                                In-progress
                            </option>
                        </select>
                    </div>
                    <div class="inpGrp ingrpSpec">
                        <span class="material-symbols-rounded ingrpSpecMa">
                            settings
                        </span>
                        <textarea rows="4" cols="50" class="input__field input__field--textarea"
                            name="tskDescription"><?= htmlspecialchars($tskData['description']) ?></textarea>
                    </div>
                    <div class="inpGrp btnfprio">
                        <button type="submit" class="frmBTN btn" name="edittsk">Edit Task</button>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
    <div class="tasks-display">
        <div class="tasksWrapper">
            <div class="taskDisplayTitle">
                <div class="spanGrp">
                    <span>📖 Task History & Active List </span>
                    <i>🎯 Manage Your Tasks – Active & Archived</i>
                </div>
                <span class="material-symbols-rounded tskCloseTr">
                    close
                </span>
            </div>
            <?php if (isset($allTasks) && count($allTasks) > 0): ?>
                <?php foreach ($allTasks as $dataKey): ?>
                    <div class="tskGroup">
                        <h4><?= htmlspecialchars($dataKey['title']) ?></h4>
                        <h5 class="<?= htmlspecialchars($dataKey['status']) ?>"><?= htmlspecialchars($dataKey['status']) ?></h5>
                        <hr>
                        <p><?= htmlspecialchars($dataKey['description']) ?></p>
                        <div class="tskGroupButtons">
                            <?php if ($dataKey['status'] == 'in-progress' || $dataKey['status'] == 'pending'): ?>
                                <a href="?TSKXXX=<?= htmlspecialchars($dataKey['id']) ?>"><button
                                        class="tskEditGrp">Edit</button></a>
                            <?php endif; ?>
                            <a href="?TSKDLXXX=<?= htmlspecialchars($dataKey['id']) ?>"><button class="tskDeleteGrp"
                                    name="TSKDLXXXBTN">Delete</button></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="taskDisplayCenterDum">👀 No tasks found! Add a task to get started</p>
            <?php endif; ?>
        </div>
    </div>
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