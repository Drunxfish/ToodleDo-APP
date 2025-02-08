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

    public function randWelcomeMsg()
    {
        $greet = [
            "Welcome to your new task manager! 🎉",
            "Hello! Welcome to your new task manager 🎊",
            "Welcome to your new task manager! 🎈",
            "Hello! Welcome to your new task manager 🎉",
            "Welcome to your new task manager! 🎊"
        ];

        return $greet[array_rand($greet)];
    }


    // function to handle user registrations
    public function signupUser($username, $email, $password)
    {
        $hashedPWD = password_hash($password, PASSWORD_DEFAULT);

        // inserts  data or not and returns boolean accordingly
        return $this->pdo->run("INSERT INTO users (username, email, password) 
        VALUES (:username, :email, :password)",
            [
                "username" => $username,
                "email" => $email,
                "password" => $hashedPWD,
            ]
        );
    }

    // returns user data
    public function logUser($email)
    {
        return $this->pdo->run("SELECT * FROM users WHERE email = :email", ["email" => $email])->fetch();
    }



    // returns email or false boolean
    public function selectEmail($email)
    {
        return $this->pdo->run("SELECT email FROM users WHERE email = :email", ['email' => $email])->fetch();
    }

}
