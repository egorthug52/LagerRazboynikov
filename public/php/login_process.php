<?php
include '../db/db.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            echo json_encode([
                'message' => 'Вход успешен!',
                'status' => 'success'
            ]);
        } else {
            echo json_encode([
                'message' => 'Неверное имя пользователя или пароль!',
                'status' => 'error'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка базы данных: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Неверный метод запроса!'
    ]);
}
?>