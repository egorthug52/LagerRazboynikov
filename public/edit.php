<?php
include './db/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    isset($_GET['isAdmin']) ? $isAdmin = $_GET['isAdmin'] : $isAdmin = 1;

    $stmt = $conn->prepare(
        "SELECT 
            patients.*, 
            diseases.mkb_kod mkb_kod, 
            diseases.name diag_name,
            diseases.id diag_id,
            patient_files.file_name,
            patient_files.file_path
        FROM 
            patients 
        LEFT JOIN 
            diseases 
        ON 
            patients.diagnosis = diseases.id
        LEFT JOIN 
            patient_files 
        ON 
            patients.id = patient_files.patient_id
        WHERE patients.id = :patient_id"
    );
    $stmt->execute([':patient_id' => $id]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$patient) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="background">
    <div class="container mt-5">
        <div class="centered-form">
            <h1 class="text-center mb-4">Редактирование карты <?php echo $patient['register_num']; ?></h1>
            <form action="./php/update.php" method="POST" enctype="multipart/form-data" id="patientForm">
                <input type="hidden" name="id" value="<?php echo $patient['id']; ?>">

                <div class="mb-3 input-clear inline-fields">
                    <div>
                        <label for="first_name" class="form-label">Имя</label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            value="<?php echo $patient['first_name']; ?>" required>
                    </div>
                    <div>
                        <label for="middle_name" class="form-label">Отчество</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name"
                            value="<?php echo $patient['middle_name']; ?>">
                    </div>
                    <div>
                        <label for="last_name" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="<?php echo $patient['last_name']; ?>" required>
                    </div>
                </div>

                <div class="mb-3 input-clear inline-fields">
                    <div>
                        <label for="age" class="form-label">Возраст</label>
                        <input type="number" class="form-control age-input" id="age" name="age"
                            value="<?php echo $patient['age']; ?>" min="0" max="150" required>
                    </div>
                    <div>
                        <label for="insurance_num" class="form-label">СНИЛС</label>
                        <input type="text" class="form-control" id="insurance_num" name="insurance_num"
                            value="<?php echo $patient['insurance_num']; ?>" required>
                    </div>
                </div>

                <div class="mb-3 input-clear">
                    <label for="phone_number" class="form-label">Номер телефона</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                        value="<?php echo $patient['phone_number']; ?>" required>
                </div>
                <div class="mb-3 input-clear">
                    <label for="diagnosis" class="form-label">Диагноз</label>
                    <select class="form-control" id="diagnosis" name="diagnosis" required
                    <?php if ($isAdmin == 0) {?> disabled <?php }?>>
                        <option value="<?php echo $patient['diag_id'] ?>"><?php echo $patient['mkb_kod'] . " - " . $patient['diag_name'] ?></option>
                        <?php
                        $diseases_stmt = $conn->query("SELECT * FROM diseases");
                        while ($row = $diseases_stmt->fetch(\PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $row['id'] . '">' . $row['mkb_kod'] . ' - ' . $row['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3 input-clear">
                    <label for="disease_date" class="form-label">Дата установления</label>
                    <input type="date" class="form-control" id="disease_date" name="disease_date"
                        value="<?php echo date('Y-m-d', $patient['disease_date']); ?>" required>
                </div>
                <div class="mb-3 input-clear">
                    <label for="confirmed_date" class="form-label">Дата подтверждения</label>
                    <input type="date" class="form-control" id="confirmed_date" name="confirmed_date"
                        value="<?php echo $patient['confirmed_date'] ? date('Y-m-d', $patient['confirmed_date']) : ''; ?>">
                </div>
                <div class="mb-3 input-clear">
                    <label for="cancellation_date" class="form-label">Дата отмены</label>
                    <input type="date" class="form-control" id="cancellation_date" name="cancellation_date"
                        value="<?php echo $patient['cancellation_date'] ? date('Y-m-d', $patient['cancellation_date']) : ''; ?>">
                </div>
                <div class="mb-3 input-clear">
                    <label for="patient_file" class="form-label">Файл пациента</label>
                    <input type="file" class="form-control" id="patient_file" name="patient_file">
                    <small class="form-text text-muted" id="file-status">
                        <?php 
                        if (!empty($patient['file_name']) && !empty($patient['file_path'])) {
                            echo '<p display="block">Текущий файл: ' . htmlspecialchars($patient['file_name']) . ' </p>';
                            echo ' <a href="' . htmlspecialchars($patient['file_path']) . '" download="' . htmlspecialchars($patient['file_name']) . '" class="btn btn-sm btn-outline-primary ms-2">Скачать</a>';
                            if ($isAdmin == 1) {
                                echo ' <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="deletePatientFile(' . $patient['id'] . ')">Удалить файл</button>';
                            }
                        } else {
                            echo "Файл не загружен.";
                        }
                        ?>
                    </small>
                    <div id="uploadProgress" class="progress mt-2" style="display: none;">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="uploadMessage" class="mt-2"></div>
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-primary" <?php if ($isAdmin == 0) {?> hidden <?php }?>>Сохранить</button>
                    <a href="index.php" class="btn btn-secondary">Вернуться к списку пациентов</a>
                </div>
            </form>
        </div>
    </div>
    
    </div>
    <div id="uploadMessage" class="text-center mt-2"></div>

    <script src="./js/script.js"></script>
    <script>
    $(document).ready(function() {
        <?php if ($isAdmin == 0) { ?>
            $('input, select').not('#id').prop('disabled', true);
            $('#confirmed_date, #cancellation_date').off('change');
        <?php } ?>

        $('#patientForm').on("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const progressBar = $("#uploadProgress");
            const progress = progressBar.find(".progress-bar");
            const message = $("#uploadMessage");

            $.ajax({
                url: "./php/update.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener(
                        "progress",
                        function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                progress.css("width", percentComplete + "%");
                                progressBar.show();
                            }
                        },
                        false
                    );
                    return xhr;
                },
                beforeSend: function () {
                    progressBar.show();
                    progress.css("width", "0%");
                    message.text("Загрузка...").css('color', 'black');
                },
                success: function (response) {
                    try {
                        const res = JSON.parse(response);
                        if (res.success) {
                            message.text("Пациент успешно сохранен!").css('color', 'green');
                            progress.css("width", "100%");
                            if (formData.get('patient_file') && formData.get('patient_file').size > 0) {
                                const fileName = formData.get('patient_file').name;
                                let fileStatus = '<p display="block">Текущий файл: ' + fileName + ' </p>' +
                                    ' <a href="../uploads/' + fileName + '" download="' + fileName + '" class="btn btn-sm btn-outline-primary ms-2">Скачать</a>';
                                <?php if ($isAdmin == 1) { ?>
                                    fileStatus += ' <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="deletePatientFile(<?php echo $patient['id']; ?>)">Удалить файл</button>';
                                <?php } ?>
                                $('#file-status').html(fileStatus);
                            }
                        } else {
                            message.text(res.error || "Ошибка при сохранении!").css('color', 'red');
                        }
                    } catch (e) {
                        message.text("Ошибка обработки ответа: " + e.message).css('color', 'red');
                    }
                },
                error: function (xhr) {
                    message.text("Ошибка: " + (xhr.statusText || "неизвестная ошибка")).css('color', 'red');
                },
                complete: function () {
                    setTimeout(() => {
                        progressBar.hide();
                        progress.css("width", "0%");
                        message.text("");
                    }, 2000);
                }
            });
        });
    });

    function deletePatientFile(patientId) {
        if (confirm('Вы уверены, что хотите удалить файл?')) {
            $.ajax({
                url: './php/delete_file.php',
                type: 'POST',
                data: { patient_id: patientId },
                success: function(response) {
                    try {
                        const res = JSON.parse(response);
                        if (res.success) {
                            $('#file-status').html('Файл не загружен.');
                            $('#uploadMessage').text('Файл успешно удален!').css('color', 'green');
                            setTimeout(() => $('#uploadMessage').text(''), 2000);
                        } else {
                            $('#uploadMessage').text(res.error || 'Ошибка при удалении файла!').css('color', 'red');
                        }
                    } catch (e) {
                        $('#uploadMessage').text('Ошибка обработки ответа: ' + e.message).css('color', 'red');
                    }
                },
                error: function(xhr) {
                    $('#uploadMessage').text('Ошибка: ' + (xhr.statusText || 'неизвестная ошибка')).css('color', 'red');
                }
            });
        }
    }
    </script>
</body>

</html>