<?php
include './db/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
$user_stmt->execute(['user_id' => $user_id]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

$superuser = $user['superuser'] ?? 0;
$superuser == 1 ? null : header("Location: ./php/logout.php");
$userFirstName = $user['first_name'] ?? '';
$userLastName = $user['last_name'] ?? '';
$userMiddleName = $user['middle_name'] ?? '';

$sql = "
SELECT 
    *
FROM 
    users
WHERE id <> :user_id;
";

$stmt = $conn->prepare($sql);
$stmt->execute([':user_id' => $user_id]);

$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Облачное хранилище</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body class="background">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="text-center">Список Сотрудников</h1>
            <div>
            <span>Вы вошли как <strong><?php echo $userLastName . " " . $userFirstName . " " . $userMiddleName ?></strong></span>
                <a href="./index.php" class="btn btn-primary ms-3">Реестр</a>
                <a href="./php/logout.php" class="btn btn-danger ms-3">Выйти</a>
            </div>
        </div>
        <a href="add.php" class="btn btn-primary mb-3">Зарегистрировать сотрудника</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Логин</th>
                    <th>ФИО</th>
                    <th>Контактный email</th>
                    <th>Регион</th>
                    <th>Администратор</th>
                    <th>Суперпользователь</th>
                    <th>Дата регистрации</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($employees as $row): ?>
            <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo "{$row['last_name']} {$row['first_name']} {$row['middle_name']}"; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['user_region']; ?></td>
                <td><?php echo $row['isAdmin'] == 1 ? 'Администратор' : 'Сотрудник'; ?></td>
                <td><?php echo $row['superuser'] == 1 ? 'Суперюзер' : 'Сотрдуник' ?></td>
                <td><?php echo $row['created_at'] ; ?></td>
                <td><button type="submit" class='btn btn-warning btn-sm'>Сохранить</button></td>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>