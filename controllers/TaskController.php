<?php
require_once '../config/Database.php';
require_once '../repositories/TaskRepository.php';
require_once '../services/TaskService.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$db = new Database();
$pdo = $db->getConnection();
$redis = $db->getRedisConnection();

$repo = new TaskRepository($pdo);
$service = new TaskService($repo, $redis);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));
$id = isset($_GET['id']) ? $_GET['id'] : null;

try {
    switch($method) {
        case 'GET':
            echo json_encode($service->getTasks());
            break;
        case 'POST':
            if ($service->addTask($data)) {
                http_response_code(201);
                echo json_encode(["message" => "Task Created", "status" => true]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Failed to Create Task", "status" => false]);
            }
            break;
        case 'PUT':
            if ($service->updateTask($id, $data)) {
                echo json_encode(["message" => "Task Updated", "status" => true]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Failed to Update", "status" => false]);
            }
            break;
        case 'PATCH':
            if ($service->updateStatus($id, $data)) {
                echo json_encode(["message" => "Status Updated", "status" => true]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Failed to Update Status", "status" => false]);
            }
            break;
        case 'DELETE':
            if ($service->deleteTask($id)) {
                echo json_encode(["message" => "Task Deleted", "status" => true]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Failed to Delete", "status" => false]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server DB Error: " . $e->getMessage(), "status" => false]);
}
?>