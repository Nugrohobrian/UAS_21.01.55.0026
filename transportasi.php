<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, modelization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
$request = [];

if (isset($_SERVER['PATH_INFO'])) {
    $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
}

function getConnection() {
    $host = 'localhost';
    $db   = 'transportasi';
    $user = 'root';
    $pass = ''; // Ganti dengan password MySQL Anda jika ada
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

function response($status, $data = NULL) {
    header("HTTP/1.1 " . $status);
    if ($data) {
        echo json_encode($data);
    }
    exit();
}

$db = getConnection();

switch ($method) {
    case 'GET':
        if (!empty($request) && isset($request[0])) {
            $id = $request[0];
            $stmt = $db->prepare("SELECT * FROM transportasi WHERE id = ?");
            $stmt->execute([$id]);
            $transportasi = $stmt->fetch();
            if ($transportasi) {
                response(200, $transportasi);
            } else {
                response(404, ["message" => "transportasi not found"]);
            }
        } else {
            $stmt = $db->query("SELECT * FROM transportasi");
            $transportasi = $stmt->fetchAll();
            response(200, $transportasi);
        }
        break;
    
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->brand) || !isset($data->model) || !isset($data->price)|| !isset($data->year)) {
            response(400, ["message" => "Missing required fields"]);
        }
        $sql = "INSERT INTO transportasi (brand, model, price, year) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$data->brand, $data->model, $data->price, $data->year])) {
            response(201, ["message" => "transportasi created", "id" => $db->lastInsertId()]);
        } else {
            response(500, ["message" => "Failed to create transportasi"]);
        }
        break;
    
    case 'PUT':
        if (empty($request) || !isset($request[0])) {
            response(400, ["message" => "transportasi ID is required"]);
        }
        $id = $request[0];
        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->brand) || !isset($data->model) || !isset($data->price) || !isset($data->year)) {
            response(400, ["message" => "Missing required fields"]);
        }
        $sql = "UPDATE transportasi SET brand = ?, model = ?, price = ?, year = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$data->brand, $data->model, $data->price, $data->year, $id])) {
            response(200, ["message" => "transportasi updated"]);
        } else {
            response(500, ["message" => "Failed to update transportasi"]);
        }
        break;
    
    case 'DELETE':
        if (empty($request) || !isset($request[0])) {
            response(400, ["message" => "transportasi ID is required"]);
        }
        $id = $request[0];
        $sql = "DELETE FROM transportasi WHERE id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$id])) {
            response(200, ["message" => "transportasi deleted"]);
        } else {
            response(500, ["message" => "Failed to delete transportasi"]);
        }
        break;
    
    default:
        response(405, ["message" => "Method not allowed"]);
        break;
}
?>