<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $conn = mysqli_connect("localhost", "bitrix24", "######", "reminderInTG");
  if (!$conn) {
    die("Ошибка подготовки запроса: " . mysqli_error($conn));
  }

      $funnel_id = $_REQUEST['id_funnel'];
      $stage_id = $_REQUEST['id_stage'];
  
      $sql = "DELETE FROM deals WHERE funnel = '$funnel_id' AND stage = '$stage_id'";
      $sql1 = "DELETE FROM reminders WHERE id_funnel = '$funnel_id' AND id_stage = '$stage_id'";

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
