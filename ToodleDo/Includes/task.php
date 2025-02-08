<?php

require 'db.php';
$taskDB = new Task();



class Task
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Modify overdue tasks
    public function changeOverDue($UID)
    {
        $overDue = $this->pdo->run("UPDATE tasks SET status = 'overdue' WHERE user_id = :id AND (status = 'in-progress' OR status = 'pending') AND due_date < NOW()", ["id" => $UID])->rowCount() > 0;
        if ($overDue) {
            // Feedback
            $this->pdo->feedback("Yikes.. You've missed your tasks 😒", "information");
            $this->pdo->pageRef($_SERVER['PHP_SELF']);
        }
    }


    // Delete overdue after 3 days
    public function deleteOverdue($UID)
    {
        $deleteOverdue = $this->pdo->run("DELETE FROM tasks WHERE user_id = :id AND status = 'overdue' AND updated_at < DATE_SUB(NOW(), INTERVAL 3 DAY)", ["id" => $UID])->rowCount() > 0;
        if ($deleteOverdue) {
            // Feedback
            $this->pdo->feedback("Overdue tasks older than 3 days have been deleted", "information");
            $this->pdo->pageRef($_SERVER['PHP_SELF']);
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Select Completed tasks
    public function selectCompleted($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE(due_date) AS due, status, created_at, DATE(updated_at) as up_date FROM tasks WHERE status = 'completed' AND user_id = :id", ["id" => $UID])->fetchAll();
    }


    // Select In-Progress/pending tasks
    public function selectInProgressPending($UID)
    {
        return $this->pdo->run("SELECT id, title, description, start_date, due_date, status, created_at, updated_at FROM tasks WHERE (status = 'in-progress' OR status = 'pending') AND user_id = :id", ["id" => $UID])->fetchAll();
    }

    // Select Overdue
    public function selectOverdueTasks($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE(due_date) AS due, status, created_at, updated_at FROM tasks WHERE status = 'overdue' AND user_id = :id", ["id" => $UID])->fetchAll();
    }

    // Select recently interacted tasks that aren't completed
    public function selectRecentTasks($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE(due_date) AS due, status, created_at, updated_at FROM tasks WHERE user_id = :id AND status != 'completed' ORDER BY updated_at DESC LIMIT 3", ["id" => $UID])->fetchAll();
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

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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


    // Delete task
    public function deleteTask($tskID, $UID)
    {
        return $this->pdo->run(
            "DELETE FROM tasks WHERE id = :tskID AND user_id = :UID",
            [
                'tskID' => $tskID,
                'UID' => $UID
            ]
        );
    }
}
