<?php
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $region = $_POST['user_region'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $middleName = $_POST['middle_name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    if ($stmt->fetch()) {
        echo "<script>alert('Пользователь уже существует'); window.history.back();</script>";
        exit;
    }

    $sql = "INSERT INTO users (username, password, user_region, first_name, middle_name, last_name, email) VALUES (:username, :password, :user_region, :first_name, :middle_name, :last_name, :email)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':password' => $password,
        ':user_region' => $region,
        ':first_name' => $firstName,
        ':middle_name' => $middleName,
        ':last_name' => $lastName,
        ':email' => $email,
    ]);

    header("Location: ../login.php");
}
?>