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

    $disease_date = strtotime($disease_date);
    $confirmed_date = $confirmed_date ? strtotime($confirmed_date) : null;
    $cancellation_date = $cancellation_date ? strtotime($cancellation_date) : null;

    // Получение первой буквы и добавление "**"
    $first_name_char = mb_substr($first_name, 0, 1, 'UTF-8') . '**';
    $middle_name_char = mb_substr($middle_name, 0, 1, 'UTF-8') . '**';
    $last_name_char = mb_substr($last_name, 0, 1, 'UTF-8') . '**';

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
        ':disease_date' => $disease_date,
        ':confirmed_date' => $confirmed_date,
        ':cancellation_date' => $cancellation_date,
        ':first_name_char' => $first_name_char,
        ':middle_name_char' => $middle_name_char,
        ':last_name_char' => $last_name_char,
        ':creator_id' => (int)$user_id,
        ':creator_name' => $username,
    ]);

    header("Location: index.php");
}
?>