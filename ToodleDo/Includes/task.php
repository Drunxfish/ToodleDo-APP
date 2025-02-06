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
    // Select Completed tasks
    public function selectCompleted($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE(due_date) AS due, status, created_at, updated_at FROM tasks WHERE status = 'completed' AND user_id = :id", ["id" => $UID])->fetchAll();
    }

    // Select Pending tasks
    function selectPending($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE(due_date) AS due, status, created_at, updated_at FROM tasks WHERE status = 'pending' AND user_id = :id", ["id" => $UID])->fetchAll();
    }

    // Select In-Progress 
    function selectInProgress($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE(due_date) AS due, status, created_at, updated_at FROM tasks WHERE status = 'in-progress' AND user_id = :id", ["id" => $UID])->fetchAll();
    }

    // Select Overdue
    public function selectOverdueTasks($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE(due_date) AS due, status, created_at, updated_at FROM tasks WHERE status = 'in-progress' AND user_id = :id AND due_date < NOW()", ["id" => $UID])->fetchAll();
    }

    // Select the 3 most recent tasks 
    public function selectRecentTasks($UID)
    {
        return $this->pdo->run("SELECT id, title, description, DATE(due_date) AS due, status, created_at, updated_at FROM tasks WHERE user_id = :id ORDER BY updated_at DESC LIMIT 3", ["id" => $UID])->fetchAll();
    }

    // Select task info

    public function selectTaskById($getID, $UID)
    {
        return $this->pdo->run(
            "SELECT id, title, description, DATE(start_date) as SD, DATE(due_date) AS due, status, created_at, updated_at FROM tasks WHERE id = :getID AND user_id = :id",
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
