<?php
include '../db/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM patients WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() > 0) {
            echo "Запись успешно удалена.";
        } else {
            echo "Запись с указанным ID не найдена.";
        }
    } catch (PDOException $e) {
        echo "Ошибка при удалении записи: " . $e->getMessage();
    }
} else {
    echo "ID записи не указан.";
}

header("Location: ../index.php");
exit;
?>