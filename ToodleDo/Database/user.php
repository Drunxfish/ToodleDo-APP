<?php

require 'db.php';
$userDB = new User();



class User
{
    public $pdo;

    public function __construct()
    {
        $this->pdo = new Database();
    }
}
