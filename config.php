<?php
// إعدادات قاعدة البيانات
// في InfinityFree، ستجدين هذه المعلومات في لوحة التحكم

$host = 'localhost'; // أو قد يكون 'localhost:3306'
$db = 'YOUR_DATABASE_NAME'; // استبدلي هذا باسم قاعدة البيانات
$user = 'YOUR_DATABASE_USER'; // استبدلي هذا باسم المستخدم
$password = 'YOUR_DATABASE_PASSWORD'; // استبدلي هذا بكلمة المرور

// إنشاء الاتصال
$conn = new mysqli($host, $user, $password, $db);

// التحقق من الاتصال
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// تعيين الترميز
$conn->set_charset("utf8");

?>
