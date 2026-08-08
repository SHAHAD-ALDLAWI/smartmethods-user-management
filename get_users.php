<?php
header('Content-Type: application/json');

require_once 'config.php';

// جلب جميع المستخدمين
$sql = "SELECT id, name, age, status FROM users ORDER BY id DESC";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Query error: ' . $conn->error]);
    exit;
}

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode(['success' => true, 'users' => $users]);

$conn->close();
?>
