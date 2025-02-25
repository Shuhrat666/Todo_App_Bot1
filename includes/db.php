<?php

require 'password.php';

class Db {
    private $db;
    public function __construct($db_name, $db_password, $db_username) {
        $this->db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_username, $db_password);
    }

    public function getPdo() {
        return $this->db;
    }

    public function addTask(string $task, $status) {
        $stmt = $this->db->prepare("INSERT INTO tasks(task, status) VALUES(:task, :status);");
        $stmt->bindParam(":task", $task, PDO::PARAM_STR);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
    }

    public function updateTask(string $task, $status, int $id) {
        $stmt = $this->db->prepare("UPDATE tasks SET task = :task, status = :status WHERE id = :id;");
        $stmt->bindParam(":task", $task, PDO::PARAM_STR);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }

    public function deleteTask(int $id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id;");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function tasksList() {
        $stmt = $this->db->query("SELECT * FROM tasks;");
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tasks;
    }
}
?>
