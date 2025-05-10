<?php
include '../db/db.php';

if (!isset($_POST['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Нет user_id']);
    exit;
}

$user_id = intval($_POST['user_id']);

$stmt = $conn->prepare("SELECT email FROM users WHERE id = :id");
$stmt->execute([":id" => $user_id]);
$email = $stmt->fetchColumn();

if ($email === false) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
    exit;
}

$new_password = bin2hex(random_bytes(8));

$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
if (!$stmt->execute([":password" => $hashed_password, ":id" => $user_id])) {
    echo json_encode(['success' => false, 'message' => 'Ошибка обновления пароля']);
    exit;
}

$subject = "Ваш новый пароль";
$message = "Здравствуйте!\nВаш новый пароль: $new_password";
$headers = "From: admin@rpn-diplom.ru";

if (!mail($email, $subject, $message, $headers)) {
    echo json_encode(['success' => false, 'message' => 'Ошибка отправки email']);
    exit;
}

echo json_encode(['success' => true]);
?>
