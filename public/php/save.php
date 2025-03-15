<?php
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $insurance_num = $_POST['insurance_num'];
    $age = $_POST['age'];
    $register_num = $_POST['register_num'];
    $diagnosis = $_POST['diagnosis'];
    $disease_date = $_POST['disease_date'];
    $confirmed_date = $_POST['confirmed_date'];
    $cancellation_date = $_POST['cancellation_date'];
    $username = $_POST['username'];
    $user_id = $_POST['user_id'];

    $disease_time = strtotime($disease_date);
    if ($disease_time === false) {
        echo json_encode(['error' => 'Некорректная дата установления диагноза']);
        exit;
    }
    $confirmed_time = $confirmed_date ? strtotime($confirmed_date) : null;
    $cancellation_time = $cancellation_date ? strtotime($cancellation_date) : null;

    $first_name_char = mb_substr($first_name, 0, 1, 'UTF-8') . '**';
    $middle_name_char = mb_substr($middle_name, 0, 1, 'UTF-8') . '**';
    $last_name_char = mb_substr($last_name, 0, 1, 'UTF-8') . '**';

    try {
        $sql = "INSERT INTO patients (
                    first_name, middle_name, last_name, phone_number, insurance_num, age, register_num, 
                    diagnosis, disease_date, confirmed_date, cancellation_date, first_name_char, middle_name_char, last_name_char, creator_id, creator_name
                ) VALUES (
                    :first_name, :middle_name, :last_name, :phone_number, :insurance_num, :age, :register_num, 
                    :diagnosis, :disease_date, :confirmed_date, :cancellation_date, :first_name_char, :middle_name_char, :last_name_char, :creator_id, :creator_name
                )";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':first_name' => $first_name,
            ':middle_name' => $middle_name,
            ':last_name' => $last_name,
            ':phone_number' => $phone_number,
            ':insurance_num' => $insurance_num,
            ':age' => $age,
            ':register_num' => $register_num,
            ':diagnosis' => $diagnosis,
            ':disease_date' => $disease_time,
            ':confirmed_date' => $confirmed_time,
            ':cancellation_date' => $cancellation_time,
            ':first_name_char' => $first_name_char,
            ':middle_name_char' => $middle_name_char,
            ':last_name_char' => $last_name_char,
            ':creator_id' => (int)$user_id,
            ':creator_name' => $username,
        ]);

        $patient_id = $conn->lastInsertId();

        if (isset($_FILES['patient_file']) && $_FILES['patient_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_dir = '../uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file = $_FILES['patient_file'];
            $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $error_messages = [
                    UPLOAD_ERR_INI_SIZE => 'Файл превышает максимальный размер, указанный в php.ini',
                    UPLOAD_ERR_FORM_SIZE => 'Файл превышает максимальный размер, указанный в форме',
                    UPLOAD_ERR_PARTIAL => 'Файл был загружен частично',
                    UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная директория для загрузки',
                    UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск',
                    UPLOAD_ERR_EXTENSION => 'Загрузка файла остановлена расширением PHP'
                ];
                $error_msg = $error_messages[$file['error']] ?? 'Неизвестная ошибка загрузки файла';
                echo json_encode(['error' => $error_msg]);
                exit;
            }

            if (!in_array($file['type'], $allowed_types)) {
                echo json_encode(['error' => 'Недопустимый тип файла!']);
                exit;
            }

            if ($file['size'] > 5242880) {
                echo json_encode(['error' => 'Файл слишком большой! Максимальный размер: 5MB']);
                exit;
            }

            $file_name = basename($file['name']);
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $uniq_file_name = uniqid() . '.' . $file_ext;
            $file_path = $upload_dir . $uniq_file_name;

            if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                echo json_encode(['error' => 'Ошибка при сохранении файла на сервере!']);
                exit;
            }

            $file_stmt = $conn->prepare("INSERT INTO patient_files (patient_id, file_path, file_name) VALUES (:patient_id, :file_path, :file_name)");
            $file_stmt->execute([
                ':patient_id' => $patient_id,
                ':file_path' => $file_path,
                ':file_name' => $file_name
            ]);
        }

        echo json_encode(['success' => true]);
        exit;

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Ошибка базы данных: ' . $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['error' => 'Неверный метод запроса!']);
    exit;
}
?>