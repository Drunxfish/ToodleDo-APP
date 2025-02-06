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
            echo $e->getMessage();
        }
    }


    // runs (prepared) queries, but faster
    public function run($sql, $plcd = null)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($plcd);
        return $stmt; # returns boolean or data, depending on the query
    }
}
;

