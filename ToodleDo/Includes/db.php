<?php

// DB connection
class Database{
    public $pdo;
    # connection dets
    public function __construct($db = "toodledo", $user = "root", $pwd = "", $host = "127.0.0.1:3306")
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
        } catch (PDOException $e) {
            // echo $e->getMessage();
        }
    }


    // runs (prepared) queries, but faster
    public function run($sql, $plcd = null){
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($plcd); 
        return $stmt; # returns boolean or data, depending on the query
    }
};

