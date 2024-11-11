<?php

$conn = mysqli_connect("localhost", "bitrix24", "Xzup2jWB", "reminderInTG");

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Запрос на выборку всех записей
$sql = "SELECT * FROM deals";
$result = $conn->query($sql);
$sql1 = "SELECT * FROM tokens";
$result1 = $conn->query($sql1);

// Проверка результата запроса
while ($row1 = mysqli_fetch_assoc($result1)) {
if ($result->num_rows > 0) {
    // Перебираем записи
    while ($row = $result->fetch_assoc()) {
        // Проверка условия
        if ($row["dealscol"] <= 0 && $row["priorit"] == 1) { 
          $sql4 = "UPDATE deals SET dealscol = 60 WHERE id_deal = " . $row["id_deal"]; 
          $result4 = mysqli_query($conn, $sql4);
          
          // Проверка результата запроса
          if ($result4) {
            echo "Значения в колонке dealscol успешно обновлены!";
          } else {
            echo "Ошибка обновления: " . mysqli_error($conn);
          }     
            $text = 'Ваша сделка https://iclect.bitrix24.ru/crm/deal/details/'.$row["id_deal"].'/ была просрочена';
            $url = 'https://api.telegram.org/bot'.$row1['token'].'/sendMessage?chat_id='.$row1['id_chat'].'&text='.$text;
            $ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  echo 'Ошибка: ' . curl_error($ch);
} else {
  echo $response;
}

curl_close($ch);
        }
     else if ($row["dealscol"] <= 0 && $row["priorit"] == 2) {
      $sql2 = "SELECT * FROM deals WHERE priorit = 1 AND id_deal = " . $row['id_deal'];
        $result2 = $conn->query($sql2); 
        if ($result2->num_rows > 0) {  
    } 
    else {
      $sql5 = "UPDATE deals SET dealscol = 60 WHERE id_deal = " . $row["id_deal"]; 
      $result5 = mysqli_query($conn, $sql5);
      
      // Проверка результата запроса
      if ($result5) {
        echo "Значения в колонке dealscol успешно обновлены!";
      } else {
        echo "Ошибка обновления: " . mysqli_error($conn);
      }  
        $text = 'Ваша сделка https://iclect.bitrix24.ru/crm/deal/details/'.$row["id_deal"].'/ была просрочена';
            $url = 'https://api.telegram.org/bot'.$row1['token'].'/sendMessage?chat_id='.$row1['id_chat'].'&text='.$text;
            $ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  echo 'Ошибка: ' . curl_error($ch);
} else {
  echo $response;
}

curl_close($ch);
    }
} 
else if ($row["dealscol"] <= 0 && $row["priorit"] == 3) {
  $sql3 = "SELECT * FROM deals WHERE (priorit = 1 OR priorit = 2) AND id_deal = " . $row['id_deal'];
    $result3 = $conn->query($sql3); 
    if ($result3->num_rows > 0) { 
} else {
  $sql6 = "UPDATE deals SET dealscol = 60 WHERE id_deal = " . $row["id_deal"]; 
  $result6 = mysqli_query($conn, $sql6);
  
  // Проверка результата запроса
  if ($result6) {
    echo "Значения в колонке dealscol успешно обновлены!";
  } else {
    echo "Ошибка обновления: " . mysqli_error($conn);
  }  
    $text = 'Ваша сделка https://iclect.bitrix24.ru/crm/deal/details/'.$row["id_deal"].'/ была просрочена';
    $url = 'https://api.telegram.org/bot'.$row1['token'].'/sendMessage?chat_id='.$row1['id_chat'].'&text='.$text;
    $ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
echo 'Ошибка: ' . curl_error($ch);
} else {
echo $response;
}

curl_close($ch);
}
}
}
}
}

// Закрытие соединения
$conn->close();