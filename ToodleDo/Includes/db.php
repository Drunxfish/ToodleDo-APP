<?php

// DB connection
class Database
{
    public $pdo;
    # connection dets
    public function __construct($db = "toodledo", $user = "root", $pwd = "", $host = "127.0.0.1:3306")
    {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pwd, $options);
        } catch (PDOException $e) {
            // echo $e->getMessage();
        }
    }


    // runs (prepared) queries, but faster
    public function run($sql, $plcd = null)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($plcd);
        return $stmt; # returns boolean or data, depending on the query
    }

    // checks if users are logged and redirects
    public function sendToDashboard()
    {
        // sends logged users to dashboard
        if (isset($_SESSION['logged']) && isset($_SESSION['id'])) {
            $this->feedback("You need to log out first to perform this action.", "information");
            $this->pageRef("dashboard.php");
        }
    }

    // checks if wheather or not users are logged  or their ids are set to session and redirects them accordingly
    public function sendAway()
    {
        // sends logged users to dashboard
        if (!isset($_SESSION['logged']) || !isset($_SESSION['id'])) {
            // feedback/return
            $this->feedback("You Are Not Authorised!", 'cross');
            $this->pageRef('login.php');
        }
    }

    // takes feedback message and icon name, afterwards displays toast if userfeedback session isset
    public function feedback($msg, $icon)
    {
        // Feedback
        $_SESSION['userFeedback'] = [
            'message' => $msg,
            'icon' => $icon
        ];
    }

    // redirects user to the given location, and exits the code
    public function pageRef($loc)
    {
        // header
        header("Location: " . strval($loc));
        exit();
    }
}
