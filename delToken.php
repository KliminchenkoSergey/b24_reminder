<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $conn = mysqli_connect("localhost", "bitrix24", "####", "reminderInTG");
  if (!$conn) {
    die("Ошибка подготовки запроса: " . mysqli_error($conn));
  }

      $token = $_REQUEST['token'];
      $id_chat = $_REQUEST['id_chat'];
  
      $sql = "DELETE FROM tokens WHERE token = '$token' AND id_chat = '$id_chat'";

      // Выполнение запроса
      if ($conn->query($sql) === TRUE) {
          echo "Данные успешно удалены";
      } else {
          echo "Ошибка при удалении данных: " . $conn->error;
      }
      
      // Закрытие соединения
      $conn->close();
      header('Location: index.php');
      exit; 
    }
      ?>
