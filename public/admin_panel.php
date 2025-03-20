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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="background">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="text-center">Список Сотрудников</h1>
            <div>
                <span>Вы вошли как
                    <strong><?php echo $userLastName . " " . $userFirstName . " " . $userMiddleName ?></strong></span>
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
                <?php foreach ($employees as $user_row): ?>
                    <tr>
                        <td contenteditable="false" data-field="username"><?php echo $user_row['username']; ?></td>
                        <td contenteditable="false" data-field="full_name">
                            <?php echo "{$user_row['last_name']} {$user_row['first_name']} {$user_row['middle_name']}"; ?>
                        </td>
                        <td contenteditable="true" data-field="email"><?php echo $user_row['email']; ?></td>
                        <td contenteditable="true" data-field="user_region">
                            <select class="form-control user_region" name="user_region_<?php echo $user_row['id']; ?>" required>
                                <?php
                                $stmt = $conn->query("SELECT * FROM regions");
                                while ($region_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($region_row['region_code'] == $user_row['user_region']) ? 'selected' : '';
                                    echo '<option value="' . $region_row['region_code'] . '" ' . $selected . '>' . $region_row['region_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td contenteditable="true" data-field="isAdmin">
                            <select data-field="isAdmin" class="form-select form-select-sm">
                                <option value="0" <?php echo $user_row['isAdmin'] == 0 ? 'selected' : ''; ?>>Сотрудник
                                </option>
                                <option value="1" <?php echo $user_row['isAdmin'] == 1 ? 'selected' : ''; ?>>Администратор
                                </option>
                            </select>
                        </td>
                        <td contenteditable="true" data-field="superuser">
                            <select data-field="superuser" class="form-select form-select-sm">
                                <option value="0" <?php echo $user_row['superuser'] == 0 ? 'selected' : ''; ?>>Сотрудник
                                </option>
                                <option value="1" <?php echo $user_row['superuser'] == 1 ? 'selected' : ''; ?>>Администратор
                                </option>
                            </select>
                        </td>
                        <td><?php echo $user_row['created_at']; ?></td>
                        <td><button type="submit" class='btn btn-warning btn-sm'>Сохранить</button></td>
                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="./js/script.js"></script>
</body>

</html>