<?php
include './db/db.php';

session_start();

$user_id = $_SESSION['user_id'];
$user_stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
$user_stmt->execute(['user_id' => $user_id]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

$superuser = $user['superuser'] ?? 0;
$superuser == 1 ? null : header("Location: ./index.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare(
        "SELECT 
            u.*, 
            r.region_code,
            r.region_name
        FROM 
            users u 
        LEFT JOIN 
            regions r 
        ON 
            u.user_region = r.region_code
        WHERE u.id = :user_id"
    );
    $stmt->execute([':user_id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Пациент не найден.";
        exit;
    }
} else {
    echo "ID пациента не указан.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Редактировать пациента</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="background">
    <div class="container mt-5">
        <div class="centered-form">
            <h1 class="text-center mb-4">Редактирование пользователя <?php echo $user['username']; ?></h1>
            <form action="./php/update_user.php" method="POST" enctype="multipart/form-data" id="userForm">
                <input type="hidden" name="user_id" value="<?php echo $id; ?>">
                <hr>
                <div class="mb-3 input-clear inline-fields">
                    <div>
                        <label for="first_name" class="form-label">Имя</label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            value="<?php echo $user['first_name']; ?>" required>
                    </div>
                    <div>
                        <label for="middle_name" class="form-label">Отчество</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name"
                            value="<?php echo $user['middle_name']; ?>">
                    </div>
                    <div>
                        <label for="last_name" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="<?php echo $user['last_name']; ?>" required>
                    </div>
                </div>

                <div class="mb-3 input-clear inline-fields">
                    <div class="mb-3 input-clear">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email"
                            value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="mb-3 input-clear">
                        <label for="user_region" class="form-label">Регион</label>
                        <select class="form-control" id="user_region" name="user_region" required <?php if ($isAdmin == 0) { ?>
                            disabled <?php } ?>>
                        <option value="<?php echo $user['user_region'] ?>">
                            <?php echo $user['region_name'] ?></option>
                        <?php
                        $regions_stmt = $conn->query("SELECT * FROM regions");
                        while ($row = $regions_stmt->fetch(\PDO::FETCH_ASSOC)) {
                            echo '<option value="'. $row['region_code']. '">'. $row['region_name']. '</option>';
                        }
                        ?>
                    </select>
                    </div>
                </div>
                <hr>
                <div class="mb-3 input-clear inline-fields">
                    <div class="mb-3 input-clear">
                        <label for="idAdmin" class="form-label">Администратор</label>
                        <select name="isAdmin" id="isAdmin" class="form-control">
                            <option value="0" <?php echo $user['isAdmin'] == 0?'selected' : '';?>>Нет</option>
                            <option value="1" <?php echo $user['isAdmin'] == 1?'selected' : '';?>>Да</option>
                        </select>
                    </div>
                    <div class="mb-3 input-clear">
                        <label for="superuser" class="form-label">Суперпользователь</label>
                        <select name="superuser" id="superuser" class="form-control" disabled>
                            <option value="0" <?php echo $user['superuser'] == 0?'selected' : '';?>>Нет</option>
                            <option value="1" <?php echo $user['superuser'] == 1?'selected' : '';?>>Да</option>
                        </select>
                    </div>
                </div>

                <div id="uploadMessage" class="text-center mt-2"></div>
                
                <div class="button-container">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="./admin_panel.php" class="btn btn-secondary">Вернуться к списку пациентов</a>
                </div>
            </form>
        </div>
    </div>

    </div>

    <script src="./js/script.js"></script>
</body>

</html>