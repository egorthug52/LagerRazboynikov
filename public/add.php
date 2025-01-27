<?php 
include 'db.php';

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
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="background">
    <div class="container mt-5">
        <div class="centered-form">
            <h1 class="text-center mb-4">Добавить пациента</h1>
            <form action="save.php" method="POST" id="patientForm">
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
                    <input type="date" class="form-control" id="confirmed_date" name="confirmed_date" required>
                </div>
                <div class="mb-3 input-clear">
                    <label for="cancellation_date" class="form-label">Дата отмены</label>
                    <input type="date" class="form-control" id="cancellation_date" name="cancellation_date" required>
                </div>
                <div class="button-container">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="index.php" class="btn btn-secondary">Вернуться к списку пациентов</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const diseaseDate = $('#disease_date');
            const confirmedDate = $('#confirmed_date');
            const cancellationDate = $('#cancellation_date');

            $('#diagnosis').select2({
                placeholder: "Выберите диагноз",
                allowClear: true
            });

            // Функция для блокировки и снятия обязательности
            function toggleFields() {
                if (confirmedDate.val()) {
                    cancellationDate.prop('disabled', true).removeAttr('required');
                } else if (cancellationDate.val()) {
                    confirmedDate.prop('disabled', true).removeAttr('required');
                } else {
                    confirmedDate.prop('disabled', false).attr('required', true);
                    cancellationDate.prop('disabled', false).attr('required', true);
                }
            }

            // Обработка изменения дат
            function validateDates() {
                if (confirmedDate.val() && new Date(confirmedDate.val()) < new Date(diseaseDate.val())) {
                    alert('Дата подтверждения не может быть раньше даты установления диагноза!');
                    confirmedDate.val('');
                    toggleFields(); // Разблокировать поля после очистки
                }
                if (cancellationDate.val() && new Date(cancellationDate.val()) < new Date(diseaseDate.val())) {
                    alert('Дата отмены не может быть раньше даты установления диагноза!');
                    cancellationDate.val('');
                    toggleFields(); // Разблокировать поля после очистки
                }
            }

            confirmedDate.on('change', function () {
                validateDates();
                toggleFields();
            });

            cancellationDate.on('change', function () {
                validateDates();
                toggleFields();
            });

            // Инициализация при загрузке страницы
            toggleFields();

            // Ограничения для поля first_name
            $('#first_name').on('input', function () {
                let value = $(this).val();

                // Удаление цифр и некорректных символов
                value = value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
                $(this).val(value);
                // Каждое слово с большой буквы
                $(this).val(value.replace(/(^|\s)\S/g, char => char.toUpperCase()));
            });

            // Ограничения для поля middle_name
            $('#middle_name').on('input', function () {
                let value = $(this).val();

                // Удаление цифр и некорректных символов
                value = value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
                $(this).val(value);
                // Каждое слово с большой буквы
                $(this).val(value.replace(/(^|\s)\S/g, char => char.toUpperCase()));
            });

            // Ограничения для поля last_name
            $('#last_name').on('input', function () {
                let value = $(this).val();

                // Удаление цифр и некорректных символов
                value = value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
                $(this).val(value);
                // Каждое слово с большой буквы
                $(this).val(value.replace(/(^|\s)\S/g, char => char.toUpperCase()));
            });

            // Ограничения для поля phone_number
            $('#phone_number').on('input', function () {
                let value = $(this).val();

                // Сохраняем первые два символа "+7"
                if (value.length < 2 || value.substring(0, 2) !== '+7') {
                    value = '+7' + value.substring(2);
                }

                // Удаление всех символов, кроме цифр
                value = value.substring(0, 2) + value.substring(2).replace(/\D/g, '');

                // Ограничение на 11 цифр (не считая "+7")
                if (value.length > 12) { // "+7" + 11 цифр = 13 символов
                    value = value.substring(0, 12);
                }

                $(this).val(value);
            });

            // Блокировка изменения первых двух символов
            $('#phone_number').on('keydown', function (e) {
                if (e.target.selectionStart < 2) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>