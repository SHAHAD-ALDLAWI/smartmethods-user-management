<?php
header('Content-Type: application/json');

require_once 'config.php';

// التحقق من الطلب POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// الحصول على البيانات من الطلب
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['name']) || !isset($input['age'])) {
    echo json_encode(['success' => false, 'message' => 'Name and age are required']);
    exit;
}

$name = trim($input['name']);
$age = intval($input['age']);

// التحقق من صحة البيانات
if (empty($name) || $age <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

// إدراج المستخدم الجديد
$sql = "INSERT INTO users (name, age, status) VALUES (?, ?, 0)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare error: ' . $conn->error]);
    exit;
}

$stmt->bind_param("si", $name, $age);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
