<?php include './db/db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="background">
    <div class="centered-form mt-5">
        <h1 class="text-center">Вход</h1>
        <form action="./php/login_process.php" method="POST" id="loginForm">
            <div class="mb-3">
                <label for="username" class="form-label">Имя пользователя</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Войти</button>
        </form>
    </div>
    <script>
        $('#loginForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: './php/login_process.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = './index.php';
                    } else {
                        alert('Неправильное имя пользователя или пароль');
                    }
                }
            });
        })
    </script>
</body>
</html>