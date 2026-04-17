<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $stmt = $pdo->prepare("SELECT * FROM tasks ORDER BY id DESC");
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->title) && !empty($data->description)) {
            $stmt = $pdo->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
            $status = isset($data->status) ? $data->status : 'To Do';
            
            if($stmt->execute([$data->title, $data->description, $status])) {
                echo json_encode(["message" => "Task Created", "status" => true]);
            } else {
                echo json_encode(["message" => "Task Not Created", "status" => false]);
            }
        } else {
            echo json_encode(["message" => "Incomplete Data", "status" => false]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->id) && !empty($data->status)) {
            $stmt = $pdo->prepare("UPDATE tasks SET title=?, description=?, status=? WHERE id=?");
            
            if($stmt->execute([$data->title, $data->description, $data->status, $data->id])) {
                echo json_encode(["message" => "Task Updated", "status" => true]);
            } else {
                echo json_encode(["message" => "Task Not Updated", "status" => false]);
            }
        } else {
            echo json_encode(["message" => "Incomplete Data", "status" => false]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->id)) {
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id=?");
            
            if($stmt->execute([$data->id])) {
                echo json_encode(["message" => "Task Deleted", "status" => true]);
            } else {
                echo json_encode(["message" => "Task Not Deleted", "status" => false]);
            }
        } else {
            echo json_encode(["message" => "ID Not Found", "status" => false]);
        }
        break;

    default:
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}
?>