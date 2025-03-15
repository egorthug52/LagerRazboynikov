<?php
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['patient_id'])) {
    $patient_id = $_POST['patient_id'];

    try {
        // Получаем информацию о файле
        $stmt = $conn->prepare("SELECT file_path FROM patient_files WHERE patient_id = :patient_id");
        $stmt->execute([':patient_id' => $patient_id]);
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($file && file_exists($file['file_path'])) {
            // Удаляем файл с сервера
            unlink($file['file_path']);
        }

        // Удаляем запись из базы данных
        $delete_stmt = $conn->prepare("DELETE FROM patient_files WHERE patient_id = :patient_id");
        $delete_stmt->execute([':patient_id' => $patient_id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Ошибка базы данных: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Неверный запрос!']);
}
exit;
?>