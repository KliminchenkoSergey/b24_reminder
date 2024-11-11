<?php
require_once (__DIR__.'/crest.php');



$params = array(
  'entityTypeId' => 2
);

$funnels = CRest::call('crm.category.list', $params);
$file_path = 'dump.txt';

$name_funnels = [];

$id_funnels = [];

$stage_name = [];

$max = [];

$i = 0;

for ($i = 0; $i <= $funnels['total'] - 1; $i++) {
  $name_funnels[] = $funnels['result']['categories'][$i]['name'];
  $id_funnels[] = $funnels['result']['categories'][$i]['id'];
}
for ($i = 0; $i <= $funnels['total'] - 1; $i++) {
  $stage_overkill = CRest::call('crm.dealcategory.stage.list', ['id' => $id_funnels[$i]]);
  foreach ($stage_overkill as $key => $value) {
    if ($key  === 'result'){
      $stage[] = $value;
  }
}
}

for ($i = 0; $i <= $funnels['total'] - 1; $i++) {
  $max[] = max(array_keys($stage[$i]));
}

foreach ($stage as &$innerArray) {
  foreach ($innerArray as &$item) {
      
      unset($item['SORT']);
  }
}


unset($innerArray);
unset($item);


// echo '<pre>';
// 	print_r($funnels);
// echo '</pre>';


?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Напоминалка в ТГ бот о движении сделок</title>
  <style>
           body {
               background-image: url("fon.jpg"); 
               background-repeat: no-repeat; /* Повторять фон или нет */
               background-size: cover; /* Как заполнять фон */
               background-position: center; /* Положение фона */
           }
       </style>
</head>
<body>
  <h1 style="text-align: center;">Напоминалка в ТГ бот о движении сделок</h1>
  <?php

$conn = new mysqli("localhost", "bitrix24", "Xzup2jWB", "reminderInTG");

if ($conn->connect_error) {
  die("Ошибка подключения: " . $conn->connect_error);
}
$sql = 'SELECT * FROM `tokens`';
$result = mysqli_query($conn, $sql);
while($row = $result->fetch_assoc()){
  echo '<form action="delToken.php" method="POST">';
  echo  'Ваш токен: '.$row['token'];
  echo '<br>';
  echo  'Ваш id чата: '.$row['id_chat'];
  echo  '<br>';
  echo '<input type="hidden" name="token" value="'.htmlspecialchars($row['token']) . '">';
  echo '<input type="hidden" name="id_chat" value="'.htmlspecialchars($row['id_chat']) . '">';
  echo '<button type="submit">Удалить</button></form>';
}

if ($result->num_rows > 0) {
  echo '<p> Общее правило для всех сделок</p>';
  $sql3 = "SELECT * FROM reminders WHERE funnel_name = 'all'";
  if ($result3 = mysqli_query($conn, $sql3)) {
    if ($result3->num_rows > 0) { 
      while ($row3 = $result3->fetch_assoc()) {
        echo '<form action="delAll.php" method="POST">';
        echo  '<td>';
        echo  'Минут: ' . $row3['minut'];
        echo  '&nbsp; &nbsp; &nbsp;';
        echo  'Часов: ' . $row3['hour'];
        echo  '&nbsp; &nbsp; &nbsp;';
        echo  'Дней: ' . $row3['day'];
        echo  '<button type="submit">Удалить</button></form>';
        echo  '<br><hr>';
      }
    } else {
  echo '<form action="send.php" method="POST">'; //поменять
  echo '<label for="minut">Минут:</label>

<input
type="text"
id="minut"
name="minut"
minlength="1"
maxlength="2"
size="10"
style="margin-right: 20px;"
required
value="0"
>';
echo '<label for="hour">Часов:</label>

<input
type="text"
id="hour"
name="hour"
minlength="1"
maxlength="2"
size="10"
style="margin-right: 20px;"
required
value="0"
>';
echo '<label for="day">Дней:</label>

<input
type="text"
id="day"
name="day"
minlength="1"
maxlength="2"
size="10"
style="margin-right: 20px;"
required
value="0"
>';

echo'<button type="submit">Применить</button></form>
<hr><br><br>';
    }
} else {
   // Обработка ошибки запроса к базе данных
   echo "Ошибка запроса: " . mysqli_error($conn); 
}  
  for ($p = 0; $p <= count($name_funnels) - 1; $p++) {
    echo '<p> Воронка: ' .$name_funnels[$p] .'</p>';
    $sql2 = "SELECT * FROM reminders WHERE stage_name = 'all' AND funnel_name = '".$name_funnels[$p]."'";
    if ($result2 = mysqli_query($conn, $sql2)) {
      if ($result2->num_rows > 0) { 
        while ($row2 = $result2->fetch_assoc()) {
          echo '<form action="delRec.php" method="POST">';
          echo  '<td>';
          echo  'Минут: ' . $row2['minut'];
          echo  '&nbsp; &nbsp; &nbsp;';
          echo  'Часов: ' . $row2['hour'];
          echo  '&nbsp; &nbsp; &nbsp;';
          echo  'Дней: ' . $row2['day'];
          echo ' <input type="hidden" name="id_funnel" value="'.htmlspecialchars($row2['id_funnel']) . '">';
          echo ' <input type="hidden" name="id_stage" value="0">';
          echo  '<button type="submit">Удалить</button></form>';
          echo  '<br>';
        }
      } else {
    echo '<form action="sendMessage.php" method="POST">';
    echo '<label for="minut">Минут:</label>

<input
  type="text"
  id="minut"
  name="minut"
  minlength="1"
  maxlength="2"
  size="10"
  style="margin-right: 20px;"
  required
  value="0"
>';
echo '<label for="hour">Часов:</label>

<input
  type="text"
  id="hour"
  name="hour"
  minlength="1"
  maxlength="2"
  size="10"
  style="margin-right: 20px;"
  required
  value="0"
>';
echo '<label for="day">Дней:</label>

<input
  type="text"
  id="day"
  name="day"
  minlength="1"
  maxlength="2"
  size="10"
  style="margin-right: 20px;"
  required
  value="0"
>';
echo '<input type="hidden" name="funnel_name" value="'.htmlspecialchars($name_funnels[$p]) . '">';
echo '<input type="hidden" name="funnel_id" value="'.htmlspecialchars($id_funnels[$p]). '">

<button type="submit">Применить</button></form>
<br><br>';
      }
} else {
     // Обработка ошибки запроса к базе данных
     echo "Ошибка запроса: " . mysqli_error($conn); 
}
echo '<p>Этапы воронок:</p><table>
  <thead>
    <tr>
      <th>Название этапа</th>
    </tr>
  </thead>
  <tbody>';
  foreach ($stage[$p] as $key => $value1):
  echo '<tr><td style="padding-right: 20px;">';
  echo $value1['NAME'];
  echo '</td>';
  $sql1 = "SELECT * FROM reminders WHERE stage_name = '" . mysqli_real_escape_string($conn, $value1['NAME']) . "'"; 
  if ($result1 = mysqli_query($conn, $sql1)) {
    if ($result1->num_rows > 0) { 
      while ($row1 = $result1->fetch_assoc()) {
        echo '<form action="delRec.php" method="POST">';
        echo  '<td>';
        echo  'Минут: ' . $row1['minut'];
        echo  '&nbsp; &nbsp; &nbsp;';
        echo  'Часов: ' . $row1['hour'];
        echo  '&nbsp; &nbsp; &nbsp;';
        echo  'Дней: ' . $row1['day'];
        echo ' <input type="hidden" name="id_funnel" value="'.htmlspecialchars($row1['id_funnel']) . '">';
        echo ' <input type="hidden" name="id_stage" value="'.htmlspecialchars($row1['id_stage']) . '">';
        echo  '<button type="submit">Удалить</button></form>';
        echo  '<br>';
      }
    } else {
      echo '<td><form action="sendMessageStage.php" method="POST"><label for="minut">Минут:</label>

      <input
        type="text"
        id="minut"
        name="minut"
        minlength="1"
        maxlength="2"
        size="10"
        style="margin-right: 20px;"
        required
        value="0"
      >';
      echo '<label for="hour">Часов:</label>
      
      <input
        type="text"
        id="hour"
        name="hour"
        minlength="1"
        maxlength="2"
        size="10"
        style="margin-right: 20px;"
        required
        value="0"
      >';
      echo '<label for="day">Дней:</label>
      
      <input
        type="text"
        id="day"
        name="day"
        minlength="1"
        maxlength="2"
        size="10"
        style="margin-right: 20px;"
        required
        value="0"
      >
  <input type="hidden" name="funnel_name" value="'.htmlspecialchars($name_funnels[$p]) . '">
  <input type="hidden" name="stage_name" value="'.htmlspecialchars($value1['NAME']) . '">
  <input type="hidden" name="funnel_id" value="'.htmlspecialchars($id_funnels[$p]) . '">
  <input type="hidden" name="stage_id" value="'.htmlspecialchars($value1['STATUS_ID']) . '">
  <button type="submit">Применить</button>';
    }
  } else {
    // Обработка ошибки запроса к базе данных
    echo "Ошибка запроса: " . mysqli_error($conn);
  }
  echo '</td></form>';
  
      echo '</tr>';
    endforeach;
    echo'</tbody>
</table>';
  echo '<hr>';
  }
} else {
  ?>
  <form action="obrabotka.php" method="POST">
    <label for="token">Введите токен бота (вид: 7094175135:AAHuDA22BkoDc9LEKQayxfAagQHV5SZm3q5):</label>
    <input type="text" id="token" name="token" required><br>
    <label for="token">Введите id группового чата (вид: -4588616562):</label>
    <input type="text" id="id_chat" name="id_chat" required><br>
    <button type="submit">Отправить</button>
  </form>
  <?php
}
$conn->close();

?>

</body>
</html>