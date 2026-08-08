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

if (!isset($input['id']) || !isset($input['status'])) {
    echo json_encode(['success' => false, 'message' => 'ID and status are required']);
    exit;
}

$id = intval($input['id']);
$status = intval($input['status']);

// التحقق من صحة البيانات
if ($id <= 0 || ($status !== 0 && $status !== 1)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

// تحديث حالة المستخدم
$sql = "UPDATE users SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare error: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ii", $status, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
