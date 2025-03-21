<?php
include './db/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_stmt = $conn->prepare("SELECT isAdmin FROM users WHERE id = :user_id");
$user_stmt->execute(['user_id' => $user_id]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

$isAdmin = $user['isAdmin'] ?? 0;

$sql = "
SELECT 
    patients.*, 
    diseases.mkb_kod mkb_kod, 
    diseases.name diag_name
FROM 
    patients 
LEFT JOIN 
    diseases 
ON 
    patients.diagnosis = diseases.id
";

if ($isAdmin != 1) {
    $sql .= " WHERE patients.creator_id = :user_id";
}

$stmt = $conn->prepare($sql);
if ($isAdmin != 1) {
    $stmt->execute([':user_id' => $user_id]);
} else {
    $stmt->execute();
}

$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <h1 class="text-center">Список пациентов</h1>
            <div>
                <span>Вы вошли как <strong><?php echo $_SESSION['username']; ?></strong></span>
                <a href="./php/logout.php" class="btn btn-danger ms-3">Выйти</a>
            </div>
        </div>
        <a href="add.php" class="btn btn-primary mb-3">Добавить пациента</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Рег. номер</th>
                    <th>ФИО</th>
                    <th>СНИЛС</th>
                    <th>Возраст</th>
                    <th>Диагноз</th>
                    <th>Дата установления</th>
                    <th>Дата подтверждения</th>
                    <th>Дата отмены</th>
                    <?php if ($isAdmin == 1): ?>
                        <th>Пользователь</th>
                        <th>Действия</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($patients as $row): ?>
            <tr>
                <td><?php echo $row['register_num']; ?></td>
                <td><?php echo $isAdmin == 1 ? "{$row['last_name']} {$row['first_name']} {$row['middle_name']}" : "{$row['last_name_char']} {$row['first_name_char']} {$row['middle_name_char']}"; ?></td>
                <td><?php echo $row['insurance_num']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo "{$row['mkb_kod']} - {$row['diag_name']}"; ?></td>
                <td><?php echo date('d.m.Y', $row['disease_date']); ?></td>
                <td><?php echo $row['confirmed_date'] ? date('d.m.Y', $row['confirmed_date']) : ''; ?></td>
                <td><?php echo $row['cancellation_date'] ? date('d.m.Y', $row['cancellation_date']) : ''; ?></td>
                <?php if ($isAdmin == 1): ?>
                    <td><?php echo $row['creator_name']; ?></td>
                    <td>
                        <a href='edit.php?id=<?php echo $row['id']; ?>' class='btn btn-warning btn-sm'>Редактировать</a>
                        <a href='./php/delete.php?id=<?php echo $row['id']; ?>' class='btn btn-danger btn-sm' onclick='return confirm("Вы уверены?")'>Удалить</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>