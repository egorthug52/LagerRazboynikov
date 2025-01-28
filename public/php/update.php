<?php
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $insurance_num = $_POST['insurance_num'];
    $age = $_POST['age'];
    $diagnosis = $_POST['diagnosis'];
    $disease_date = strtotime($_POST['disease_date']);
    $confirmed_date = $_POST['confirmed_date'] ? strtotime($_POST['confirmed_date']) : null;
    $cancellation_date = $_POST['cancellation_date'] ? strtotime($_POST['cancellation_date']) : null;

    // Получение первой буквы и добавление "**"
    $first_name_char = mb_substr($first_name, 0, 1, 'UTF-8') . '**';
    $middle_name_char = mb_substr($middle_name, 0, 1, 'UTF-8') . '**';
    $last_name_char = mb_substr($last_name, 0, 1, 'UTF-8') . '**';

    // SQL-запрос для обновления данных
    $sql = "UPDATE patients SET
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                phone_number = :phone_number,
                insurance_num = :insurance_num,
                age = :age,
                diagnosis = :diagnosis,
                disease_date = :disease_date,
                confirmed_date = :confirmed_date,
                cancellation_date = :cancellation_date,
                first_name_char = :first_name_char,
                middle_name_char = :middle_name_char,
                last_name_char = :last_name_char
            WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':first_name' => $first_name,
        ':middle_name' => $middle_name,
        ':last_name' => $last_name,
        ':phone_number' => $phone_number,
        ':insurance_num' => $insurance_num,
        ':age' => $age,
        ':diagnosis' => $diagnosis,
        ':disease_date' => $disease_date,
        ':confirmed_date' => $confirmed_date,
        ':cancellation_date' => $cancellation_date,
        ':first_name_char' => $first_name_char,
        ':middle_name_char' => $middle_name_char,
        ':last_name_char' => $last_name_char,
        ':id' => $id
    ]);

    header("Location: index.php");
}
?>