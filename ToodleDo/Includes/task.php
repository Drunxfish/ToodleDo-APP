<?php

require_once 'db.php';
$taskDB = new Task();

class Task
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }

    /////////////////////////////////////////////////
    ///////////// Notification messages /////////////
    private function randOverueMsg()
    {
        $messages = [
            "🚨 Heads up! Some tasks are overdue. We've moved them to the overdue section. You can always create new tasks 😊",
            "🔔 Reminder! You have overdue tasks. They've been moved to the overdue section. Feel free to create new tasks 👍",
            "⚠️ Alert! Some tasks are now overdue. Check the overdue section and create new tasks if needed 📝",
            "📅 Notice! missed some tasks and they have been moved to the overdue section. Don't worry, you can always add new ones 🆕",
            "⏰ Time's up! Certain tasks are overdue. We've moved them accordingly. You can always make new tasks ✨"
        ];
        return $messages[array_rand($messages)];
    }

    private function randCompleteMsg()
    {
        $messages = [
            "🎉 Congratulations! You've completed some tasks. They've been archived. Keep up the good work! 🎊",
            "🎈 Great job! You've completed some tasks. They've been archived. Keep it up! 🎉",
            "🎊 Awesome! You've completed some tasks. They've been archived. Keep up the good work! 🎈",
            "🎉 Well done! You've completed some tasks. They've been archived. Keep it up! 🎊",
            "🎈 Fantastic! You've completed some tasks. They've been archived. Keep up the good work! 🎉"
        ];
        return $messages[array_rand($messages)];
    }

    private function randInProgressMsg()
    {
        $messages = [
            "🚀 Your tasks have started! They're now in progress. Keep up the momentum! 🔥",
            "⏳ Time to work! Some tasks have started and are now in progress. Stay focused! 💪",
            "🔄 Tasks updated! Some pending tasks are now in progress. Let's get things done! ✅",
            "🔥 Keep going! Some tasks have moved to in progress. You're on the right track! 🚀",
            "⚡ Action time! Your pending tasks are now in progress. Keep pushing forward! 🎯"
        ];
        return $messages[array_rand($messages)];
    }


    public function greetUser($username)
    {
        if (!isset($_SESSION['greeting'])) {
            $messages = [
                "Ahoy, $username! Time to navigate through your tasks! ⛵",
                "Hello, $username! Your quest for productivity begins now! ⚔️",
                "Welcome, $username! Engaging task master mode… ✅",
                "Salutations, $username! Let’s write today’s success story! 📖"
            ];
            return $_SESSION['greeting'] = $messages[array_rand($messages)];
        }
        return $_SESSION['greeting'];
    }


    ///////////// Notification messages end /////////////
    ////////////////////////////////////////////////////



    ////////////////////////////////////////////////////
    ///////////// Managing Notifications //////////////
    //////////////////////////////////////////////////
    public function createNotification($UID, $title, $message)
    {
        $createNotification = $this->pdo->run("INSERT INTO notifications (user_id, title, message) VALUES (:id, :title, :description)", ["id" => $UID, "title" => $title, "description" => $message])->rowCount() > 0;
        if ($createNotification) {
            // Feedback
            $this->pdo->feedback("You have New Notification 💬 😊", "information");
        }
    }


    // Select Notifications
    public function selectNotifications($UID)
    {
        $qt = $this->pdo->run("SELECT id, title, message, created_at FROM notifications WHERE user_id = :id ORDER BY created_at DESC", ["id" => $UID])->fetchAll();
        return count($qt) > 0 ? $qt : false;
    }

    // Delete all Notifications
    public function deleteAllNotifications($UID)
    {
        $deleteAllNotifications = $this->pdo->run("DELETE FROM notifications WHERE user_id = :id", ["id" => $UID])->rowCount() > 0;
        if ($deleteAllNotifications) {
            // Feedback
            $this->pdo->feedback("All notifications deleted", "information");
            $this->pdo->pageRef($_SERVER['PHP_SELF']);
        }
        return $deleteAllNotifications;
    }


    /////////////////////////////////////////////////
    ///////////// Changing task status /////////////
    ///////////////////////////////////////////////

    // Modify overdue tasks
    public function changeOverDue($UID)
    {
        $overDue = $this->pdo->run("UPDATE tasks SET status = 'overdue' WHERE user_id = :id AND (status = 'in-progress' OR status = 'pending') AND due_date < NOW()", ["id" => $UID])->rowCount() > 0;
        if ($overDue) {
            // Get tasks set to overdue in the last 10 minutes
            $_SESSION['modifiedTSKS'] = $this->pdo->run("SELECT id, title, description FROM tasks WHERE user_id = :id AND status = 'overdue' AND updated_at >= NOW() - INTERVAL 1 MINUTE", ["id" => $UID])->fetchAll();
            // Feedback
            $this->createNotification($UID, "Overdue Task", $this->randOverueMsg());
            $this->pdo->pageRef($_SERVER['PHP_SELF']);
        }
    }


    // Modify completed tasks status
    public function changeCompleted($UID)
    {
        $completed = $this->pdo->run("UPDATE tasks SET status = 'archived' WHERE user_id = :id AND status = 'completed'", ["id" => $UID])->rowCount() > 0;
        if ($completed) {
            // Feedback
            $this->createNotification($UID, "Completed Task", $this->randCompleteMsg());
            $this->pdo->pageRef($_SERVER['PHP_SELF']);
        }
    }

    // Changing pending tasks to in-progress
    public function changeInprogress($UID)
    {
        $inProgress = $this->pdo->run("UPDATE tasks SET status = 'in-progress' WHERE user_id = :id AND status = 'pending' AND start_date <= NOW()", ["id" => $UID])->rowCount() > 0;
        if ($inProgress) {
            // Feedback
            $this->createNotification($UID, "Task In-progress", $this->randInProgressMsg());
            $this->pdo->pageRef($_SERVER['PHP_SELF']);
        }
    }

    // Delete overdue after 3 days
    public function deleteOverdue($UID)
    {
        $deleteOverdue = $this->pdo->run("DELETE FROM tasks WHERE user_id = :id AND status = 'overdue' AND updated_at < DATE_SUB(NOW(), INTERVAL 3 DAY)", ["id" => $UID])->rowCount() > 0;
        if ($deleteOverdue) {
            // Feedback
            $this->createNotification($UID, "Overdue Task", "Overdue task(s) have been deleted");
            $this->pdo->feedback("'Expired' Overdue task(s) have been deleted", "information");
            return $this->pdo->pageRef($_SERVER['PHP_SELF']);
        }
    }
    //////////////////////////////////////////////////////////////
    /////////// Grabbing tasks for the user dashboard ///////////
    ////////////////////////////////////////////////////////////


    // Select Completed tasks
    // if task marked as completed before its initial start_date then completion_time is returned as NULL

    public function selectAll($UID)
    {
        return $this->pdo->run("SELECT * FROM tasks  WHERE user_id = :id ORDER BY updated_at DESC", ['id' => $UID])->fetchAll();
    }


    public function selectCompleted($UID)
    {
        return $this->pdo->run("
        SELECT id, title, description, 
               DATE_FORMAT(due_date, '%Y-%m-%d') AS due_date, 
               DATE_FORMAT(due_date, '%H:%i') AS due_time, 
               status, created_at, 
               DATE_FORMAT(updated_at, '%Y-%m-%d') AS up_date, 
               DATE_FORMAT(updated_at, '%H:%i') AS up_time, 
               CASE 
                   WHEN updated_at < start_date THEN NULL
                   ELSE TIMESTAMPDIFF(MINUTE, start_date, updated_at) 
               END AS completion_time 
        FROM tasks 
        WHERE status = 'completed' AND user_id = :id
    ", ["id" => $UID])->fetchAll();
    }


    // Select In-Progress/pending tasks
    public function selectInProgressPending($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE_FORMAT(start_date, '%Y-%m-%d') AS start_date, DATE_FORMAT(start_date, '%H:%i') AS start_time, DATE_FORMAT(due_date, '%Y-%m-%d') AS due_date, DATE_FORMAT(due_date, '%H:%i') AS due_time, status, created_at, updated_at FROM tasks WHERE (status = 'in-progress' OR status = 'pending') AND user_id = :id", ["id" => $UID])->fetchAll();
    }

    // Select Overdue
    public function selectOverdueTasks($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE(due_date) AS due, status, created_at, updated_at FROM tasks WHERE status = 'overdue' AND user_id = :id", ["id" => $UID])->fetchAll();
    }

    // Select recently interacted tasks that aren't completed
    public function selectRecentTasks($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE(due_date) AS due, status, created_at, updated_at FROM tasks WHERE user_id = :id AND status NOT IN ('completed', 'archived') ORDER BY updated_at DESC LIMIT 5", ["id" => $UID])->fetchAll();
    }

    // Select task info
    public function selectTaskById($getID, $UID)
    {
        return $this->pdo->run(
            "SELECT id, title, description, start_date,due_date, status, created_at, updated_at FROM tasks WHERE id = :getID AND user_id = :id",
            [
                "getID" => $getID,
                "id" => $UID
            ]
        )->fetch();
    }

    ///////////// Adding/Editing/Deleting tasks /////////////
    // Add Task
    public function addTask($UID, $title, $description, $start_date, $due_date, $status)
    {
        return $this->pdo->run(
            "INSERT INTO tasks (user_id, title, description, start_date, due_date, status) VALUES (:user_id, :title, :description, :start_date, :due_date, :status)",
            [
                'user_id' => $UID,
                'title' => $title,
                'description' => $description,
                'start_date' => $start_date,
                'due_date' => $due_date,
                'status' => $status
            ]
        );
    }

    // Update task
    public function editTask($tskID, $UID, $title, $description, $start_date, $due_date, $status)
    {
        return $this->pdo->run(
            "UPDATE tasks 
            SET title = :title, description = :description, start_date = :start_date, due_date = :due_date, status = :status
            WHERE id = :tskID AND user_id = :UID",
            [
                'title' => $title,
                'description' => $description,
                'start_date' => $start_date,
                'due_date' => $due_date,
                'status' => $status,
                'tskID' => $tskID,
                'UID' => $UID,
            ]
        );
    }

    public function changeTaskStatus($UID, $tskID, $status)
    {
        $tskUPDT = $this->pdo->run(
            "UPDATE tasks SET status = :status WHERE id = :tskID AND user_id = :UID",
            [
                'status' => $status,
                'tskID' => $tskID,
                'UID' => $UID
            ]
        )->rowCount() > 0;

        if ($tskUPDT) {
            // Feedback
            $this->pdo->feedback("Task status updated 🎉", "information");
        } else {
            // Feedback
            $this->pdo->feedback("Oopsies... Something went wrong, please try again later", "cross");
        }
        $this->pdo->pageRef($_SERVER['PHP_SELF']);
    }

    // Delete task
    public function deleteTask($tskID, $UID)
    {
        $tskDLT = $this->pdo->run(
            "DELETE FROM tasks WHERE id = :tskID AND user_id = :UID",
            [
                'tskID' => $tskID,
                'UID' => $UID
            ]
        )->rowCount() > 0;

        //
        if ($tskDLT) {
            $this->pdo->feedback("Task deleted successfully 🥹", "check");
        } else {
            $this->pdo->feedback("Hmmmm, I know what u did there ._.", "cross");
        }
        $this->pdo->pageRef($_SERVER['PHP_SELF']);
    }
}
