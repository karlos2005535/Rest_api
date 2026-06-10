<?php
class TaskRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllTasks() {
        $stmt = $this->conn->prepare("SELECT * FROM tasks ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTask($title, $description, $status) {
        $stmt = $this->conn->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
        return $stmt->execute([$title, $description, $status]);
    }

    public function updateTask($id, $title, $description, $status) {
        $stmt = $this->conn->prepare("UPDATE tasks SET title=?, description=?, status=? WHERE id=?");
        return $stmt->execute([$title, $description, $status, $id]);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE tasks SET status=? WHERE id=?");
        return $stmt->execute([$status, $id]);
    }

    public function deleteTask($id) {
        $stmt = $this->conn->prepare("DELETE FROM tasks WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?>