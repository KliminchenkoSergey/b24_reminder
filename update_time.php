<?php

$conn = mysqli_connect("localhost", "bitrix24", "Xzup2jWB", "reminderInTG");

// Проверка подключения
if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
  }
  
  // Запрос к базе данных для обновления значений dealscol
  $sql = "UPDATE deals SET dealscol = dealscol - 1"; 
  $result = mysqli_query($conn, $sql);
  
  // Проверка результата запроса
  if ($result) {
    echo "Значения в колонке dealscol успешно обновлены!";
  } else {
    echo "Ошибка обновления: " . mysqli_error($conn);
  }
  
  // Закрытие подключения
  mysqli_close($conn);
  
  header('Location: index.php');
exit; 

  ?>