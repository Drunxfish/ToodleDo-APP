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
                    <a href="#" class="nav-link">
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
                <h2>Hello, Drunxfish</h2>
                <span>Today is monday, 5 February 2025</span>
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
                        <h3>📅 Upcoming Tasks</h3>
                        <ul class="task-list">
                            <li>Finish project report <span class="due-date">Due: Feb 10</span></li>
                            <li>Team meeting at 3 PM <span class="due-date">Due: Feb 8</span></li>
                        </ul>
                    </div>
                    <div class="task-card completed">
                        <h3>✅ Completed Tasks</h3>
                        <ul class="task-list">
                            <li>Finish project report <span class="due-date">Due: Feb 10</span></li>
                            <li>Team meeting at 3 PM <span class="due-date">Due: Feb 8</span></li>
                        </ul>
                    </div>
                    <div class="task-card overdue">
                        <h3>⚠️ Overdue Tasks</h3>
                        <ul class="task-list">
                            <li>Finish project report <span class="due-date">Due: Feb 10</span></li>
                            <li>Team meeting at 3 PM <span class="due-date">Due: Feb 8</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tskCurrent">
                <div class="tskHeader">
                    <h3>Current Tasks</h3>
                </div>
                <div class="tskBox">
                    <ul>
                        <li>
                            <span class="taskTitle">Task 1</span>
                            <span class="taskDueDate">Due: 2025-02-10</span>
                            <span class="taskStatus">In Progress</span>
                            <div class="taskActions">
                                <button class="editBtn">Edit</button>
                                <button class="deleteBtn">Delete</button>
                            </div>
                        </li>
                        <li>
                            <span class="taskTitle">Task 2</span>
                            <span class="taskDueDate">Due: 2025-02-12</span>
                            <span class="taskStatus">Pending</span>
                            <div class="taskActions">
                                <button class="editBtn">Edit</button>
                                <button class="deleteBtn">Delete</button>
                            </div>
                        </li>
                        <li>
                            <span class="taskTitle">Task 3</span>
                            <span class="taskDueDate">Due: 2025-02-15</span>
                            <span class="taskStatus">In Progress</span>
                            <div class="taskActions">
                                <button class="editBtn">Edit</button>
                                <button class="deleteBtn">Delete</button>
                            </div>
                        </li>
                    </ul>
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
                <button class="frmBTN frmBTN--icon" id="formCloserBTN">
                    <svg width="24" viewBox="0 0 24 24" height="24" xmlns="http://www.w3.org/2000/svg">
                        <path fill="none" d="M0 0h24v24H0V0z"></path>
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z">
                        </path>
                    </svg>
                </button>
            </div>
            <form method="GET">
                <div class="modal__body">
                    <div class="input">
                        <label class="input__label">Task title</label>
                        <input class="input__field" type="text" name="tskTitle" placeholder="Go to gym">
                        <p class="input__description">Try something short and descriptive</p>
                    </div>
                    <div class="input">
                        <label class="input__label">Start-Date</label>
                        <input class="input__field" type="date" name="startDate">
                    </div>
                    <div class="input">
                        <label class="input__label">Deadline</label>
                        <input class="input__field" type="date" name="dueDate">
                        <p class="input__description">Tip: set realistic deadlines :P</p>
                    </div>
                    <div class="input">
                        <label class="input__label">Description</label>
                        <textarea rows="4" cols="50" class="input__field input__field--textarea" name="tskDescription"
                            placeholder="Time to sweat it out! Get to the gym and crush your workout."></textarea>
                        <p class="input__description">Give your task a good description</p>
                    </div>
                </div>
                <div class="modal__footer">
                    <button type="submit" class="frmBTN frmBTN--primary">Create Task</button>
                </div>
            </form>
        </div>
    </div>
    <script src="./../Js/loader.js"></script>
</body>

</html>