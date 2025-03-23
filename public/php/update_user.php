<?php
include '../db/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'] ?? '';
        $user_region = $_POST['user_region'] ?? '';
        $isAdmin = $_POST['isAdmin'] ?? 0;
        $last_name = $_POST['last_name'] ?? '';
        $first_name = $_POST['first_name'] ?? '';
        $middle_name = $_POST['middle_name'] ?? '';
    
    $sql = "UPDATE users SET 
        email = :email,
        user_region = :user_region,
        isAdmin = :isAdmin,
        last_name = :last_name,
        first_name = :first_name,
        middle_name = :middle_name
        WHERE id = :id";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':user_region' => $user_region,
            ':isAdmin' => $isAdmin,
            ':last_name' => $last_name,
            ':first_name' => $first_name,
            ':middle_name' => $middle_name,
            ':id' => $_POST['user_id']?? ''
        ]);
        echo json_encode([
            'status' => 'success',
            'message' => 'Пользователь успешно обновлён'
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Неверный метод запроса'
    ]);
}
?>