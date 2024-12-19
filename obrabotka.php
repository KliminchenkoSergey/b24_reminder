<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['token'];
    $id_chat = $_POST['id_chat'];

    if (empty($token)) {
        echo "Пожалуйста, введите токен бота.";
        exit;
    }

    $conn = mysqli_connect("localhost", "bitrix24", "####", "reminderInTG");
    if (!$conn) {
        die("Ошибка подготовки запроса: " . mysqli_error($conn));
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO tokens (token, id_chat) VALUES (?,?)");
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ss", $token, $id_chat);
    if (!mysqli_stmt_execute($stmt)) {
        die("Ошибка выполнения запроса: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);

    if (!file_exists('token.txt')) {
        $file_path = 'token.txt';
        if (!touch($file_path)) {
            die("Ошибка создания файла: " . mysqli_error($conn));
        }
    }

    // Записываем токен в файл
    $file_path = 'token.txt';
    if (!file_put_contents($file_path, $token)) {
        die("Ошибка записи в файл: " . mysqli_error($conn));
    }
    mysqli_close($conn);

    header('Location: index.php');
    exit;
}

?>
