<?php

class DB
{
    public $host;
    public $username;
    public $password;
    public $database;
    public $conn;

    public function __construct()
    {
        // .env fayldan sozlamalarni yuklash
        $config = parse_ini_file(__DIR__ . '/.env');

        $this->host = $config['DB_HOST'];
        $this->username = $config['DB_USERNAME'];
        $this->password = $config['DB_PASSWORD'];
        $this->database = $config['DB_DATABASE'];
        $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
    }

    public function saveImageAddress($addressImage): bool
    {
        $query = "INSERT INTO images (path) VALUES (:addressImage)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':addressImage' => $addressImage]);
    }

    public function getLastImage()
    {
        $query = "SELECT * FROM images";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}