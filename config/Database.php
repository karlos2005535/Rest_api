<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'task_master';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo json_encode(["error" => "Connection error: " . $exception->getMessage()]);
            exit();
        }
        return $this->conn;
    }

    public function getRedisConnection() {
        // Mencegah fatal error dengan mengecek ketersediaan class Redis di PHP
        if (!class_exists('Redis')) {
            return null; // Kembalikan null jika ekstensi Redis tidak terpasang
        }

        try {
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            return $redis;
        } catch(Exception $e) {
            return null; // Fallback jika ekstensi ada tapi server Redis mati
        }
    }
}
?>