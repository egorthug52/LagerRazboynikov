<?php
include '../db/db.php';

session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['superuser']) {
    echo json_encode(['success' => false, 'message' => 'Доступ запрещен']);
    exit;
}

$user_id = $_POST['user_id'] ?? '';
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'ID пользователя не указан']);
    exit;
}

$data = [
    'username' => $_POST['username'] ?? '',
    'email' => $_POST['email'] ?? '',
    'user_region' => $_POST['user_region'] ?? '',
    'isAdmin' => $_POST['isAdmin'] ?? 0,
    'superuser' => $_POST['superuser'] ?? 0,
    'last_name' => $_POST['last_name'] ?? '',
    'first_name' => $_POST['first_name'] ?? '',
    'middle_name' => $_POST['middle_name'] ?? ''
];

$sql = "UPDATE users SET 
    username = :username,
    email = :email,
    user_region = :user_region,
    isAdmin = :isAdmin,
    superuser = :superuser,
    last_name = :last_name,
    first_name = :first_name,
    middle_name = :middle_name
    WHERE id = :id";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute(array_merge($data, ['id' => $user_id]));
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>