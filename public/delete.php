<?php
// Подключение к базе данных
include 'db.php';

// Проверка, передан ли параметр id
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Подготовка SQL-запроса для удаления записи
        $sql = "DELETE FROM patients WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Проверка, была ли удалена запись
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

// Перенаправление на страницу списка пациентов
header("Location: index.php");
exit;
?>