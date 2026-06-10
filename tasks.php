<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

switch($method) {
    case 'GET':
        try {
            $stmt = $pdo->prepare("SELECT * FROM tasks ORDER BY id DESC");
            $stmt->execute();
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            http_response_code(200);
            echo json_encode($tasks);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Gagal mengambil data database."]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->title) && !empty($data->description)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
                
                $clean_title = sanitize_input($data->title);
                $clean_desc = sanitize_input($data->description);
                $status = isset($data->status) ? sanitize_input($data->status) : 'To Do';
                
                if($stmt->execute([$clean_title, $clean_desc, $status])) {
                    http_response_code(201);
                    echo json_encode(["message" => "Tugas berhasil dibuat.", "status" => true]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(["message" => "Terjadi kesalahan pada server.", "status" => false]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Data tidak lengkap. Judul dan Deskripsi wajib diisi.", "status" => false]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->id) && !empty($data->status)) {
            try {
                $stmt = $pdo->prepare("UPDATE tasks SET title=?, description=?, status=? WHERE id=?");
                
                $clean_title = sanitize_input($data->title);
                $clean_desc = sanitize_input($data->description);
                $clean_status = sanitize_input($data->status);
                
                if($stmt->execute([$clean_title, $clean_desc, $clean_status, $data->id])) {
                    http_response_code(200);
                    echo json_encode(["message" => "Tugas berhasil diperbarui.", "status" => true]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(["message" => "Gagal memperbarui data.", "status" => false]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Data tidak lengkap.", "status" => false]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $id = isset($_GET['id']) ? $_GET['id'] : (isset($data->id) ? $data->id : null);
        
        if(!empty($id)) {
            try {
                $stmt = $pdo->prepare("DELETE FROM tasks WHERE id=?");
                
                if($stmt->execute([$id])) {
                    http_response_code(200);
                    echo json_encode(["message" => "Tugas berhasil dihapus.", "status" => true]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(["message" => "Gagal menghapus data.", "status" => false]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID Tugas tidak ditemukan.", "status" => false]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Metode tidak diizinkan."]);
        break;
}
?>