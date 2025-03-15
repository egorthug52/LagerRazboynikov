<?php
include './db/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Добавить пациента</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="background">
    <div class="container mt-5">
        <div class="centered-form">
            <h1 class="text-center mb-4">Добавить пациента</h1>
            <form action="./php/save.php" method="POST" id="patientForm" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="username" value="<?php echo $user['username']; ?>">
                <div class="mb-3 input-clear">
                    <label for="register_num" class="form-label">Регистрационный номер</label>
                    <input type="text" class="form-control" id="register_num" name="register_num" required>
                </div>

                <div class="mb-3 input-clear inline-fields">
                    <div>
                        <label for="first_name" class="form-label">Имя</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div>
                        <label for="middle_name" class="form-label">Отчество</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name">
                    </div>
                    <div>
                        <label for="last_name" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                </div>

                <div class="mb-3 input-clear inline-fields">
                    <div>
                        <label for="age" class="form-label">Возраст</label>
                        <input type="number" class="form-control age-input" id="age" name="age" min="0" max="150"
                            required>
                    </div>
                    <div>
                        <label for="insurance_num" class="form-label">СНИЛС</label>
                        <input type="text" class="form-control" id="insurance_num" name="insurance_num" required>
                    </div>
                </div>

                <div class="mb-3 input-clear">
                    <label for="phone_number" class="form-label">Номер телефона</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="+7" required>
                </div>
                <div class="mb-3 input-clear">
                    <label for="diagnosis" class="form-label">Диагноз</label>
                    <select class="form-control" id="diagnosis" name="diagnosis" required>
                        <option value="">Выберите диагноз</option>
                        <?php
                        $stmt = $conn->query("SELECT * FROM diseases");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $row['id'] . '">' . $row['mkb_kod'] . ' - ' . $row['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3 input-clear">
                    <label for="disease_date" class="form-label">Дата установления</label>
                    <input type="date" class="form-control" id="disease_date" name="disease_date" required>
                </div>
                <div class="mb-3 input-clear">
                    <label for="confirmed_date" class="form-label">Дата подтверждения</label>
                    <input type="date" class="form-control" id="confirmed_date" name="confirmed_date">
                </div>
                <div class="mb-3 input-clear">
                    <label for="cancellation_date" class="form-label">Дата отмены</label>
                    <input type="date" class="form-control" id="cancellation_date" name="cancellation_date">
                </div>
                <div class="mb-3 input-clear">
                    <label for="patient_file" class="form-label">Загрузить документы пациента</label>
                    <input type="file" class="form-control" id="patient_file" name="patient_file"
                        accept=".pdf,.doc,.docx,.jpg,.png">
                    <div id="uploadProgress" class="progress mt-2" style="display: none;">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="uploadMessage" class="mt-2"></div>
                </div>
                <div class="button-container">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="index.php" class="btn btn-secondary">Вернуться к списку пациентов</a>
                </div>
            </form>
        </div>
    </div>

    <script src="./js/script.js"></script>
</body>

</html>