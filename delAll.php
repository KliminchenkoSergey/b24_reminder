<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $conn = mysqli_connect("localhost", "bitrix24", "Xzup2jWB", "reminderInTG");
  if (!$conn) {
    die("Ошибка подготовки запроса: " . mysqli_error($conn));
  }
  
      $sql = "DELETE FROM deals WHERE funnel = '0' AND stage = 'all'";
      $sql1 = "DELETE FROM reminders WHERE funnel_name = 'all'";

      // Выполнение запроса
      if ($conn->query($sql) === TRUE) {
          echo "Данные успешно удалены";
      } else {
          echo "Ошибка при удалении данных: " . $conn->error;
      }
      if ($conn->query($sql1) === TRUE) {
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
