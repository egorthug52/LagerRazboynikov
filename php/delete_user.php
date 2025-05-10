<?php
include '../db/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() > 0) {
            // Успешное удаление → перенаправляем с сообщением
            header("Location: ../admin_panel.php?status=success");
            exit;
        } else {
            // Не найдено → перенаправляем с сообщением
            header("Location: ../admin_panel.php?status=notfound");
            exit;
        }
    } catch (PDOException $e) {
        // Ошибка БД → перенаправляем с сообщением
        header("Location: ../admin_panel.php?status=error");
        exit;
    }
} else {
    // ID не указан → перенаправляем с сообщением
    header("Location: ../admin_panel.php?status=noid");
    exit;
}
