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
}
