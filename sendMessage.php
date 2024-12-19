<?php

require_once (__DIR__.'/crest.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $conn = mysqli_connect("localhost", "bitrix24", "######", "reminderInTG");
  if (!$conn) {
    die("Ошибка подготовки запроса: " . mysqli_error($conn));
  }
$funnel_name = $_REQUEST['funnel_name'];
$stage_name = 'all';
$funnel_id = $_REQUEST['funnel_id'];
$stage_id = '0';
$minut = $_REQUEST['minut'];
$hour = $_REQUEST['hour'];
$day = $_REQUEST['day'];
$prior = 2;

$delay = ($day * 24 * 60 * 60) + ($hour * 60 * 60) + ($minut * 60);

$currentTimesec = time();
$currentDateTime = date('Y-m-d H:i:s', $currentTimesec);
$newTimestamp = $currentTimesec + $delay; 
$newDateTime = date('Y-m-d H:i:s', $newTimestamp);
$datetime1 = new DateTime($currentDateTime);
$datetime2 = new DateTime($newDateTime);
$diff = $datetime2->getTimestamp() - $datetime1->getTimestamp();
$minutes = round($diff / 60);

$stmt = mysqli_prepare($conn, "INSERT INTO reminders (funnel_name, stage_name, id_funnel, id_stage, minut, hour, day, sec, dealscol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
  die("Ошибка подготовки запроса: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "ssisiiiii", $funnel_name, $stage_name, $funnel_id, $stage_id, $minut, $hour, $day, $delay, $minutes);
if (!mysqli_stmt_execute($stmt)) {
  die("Ошибка выполнения запроса: " . mysqli_error($conn));
mysqli_stmt_close($stmt);
mysqli_close($conn);
}


$start = 0;
$limit = 50; // Ограничение на количество элементов за один запрос

$deals = array();

do {
  $params = array(
    'filter' => array(
      'CATEGORY_ID' => $funnel_id, // Фильтр по стадии сделки
      'CLOSED' => 'N',
    ),
    'select' => array(
      "ID"
    ),
    'start' => $start, // Добавляем параметр start
    'limit' => $limit, // Добавляем параметр limit
  );

  $response = CRest::call('crm.deal.list', $params);

  if (isset($response['result'])) {
      $deals = array_merge($deals, $response['result']);
  }

  $start += $limit; // Увеличиваем start для следующего запроса

} while (count($response['result']) === $limit); // Продолжаем цикл, пока не получим менее 50 элементов
// $file_path = 'time.txt';
// $data = json_encode($deals);
//   file_put_contents($file_path, $data);

$stmt1 = mysqli_prepare($conn, "INSERT INTO deals (id_deal, date_start, date_end, dealscol, funnel, stage, priorit) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt1) {
  die("Ошибка подготовки запроса: " . mysqli_error($conn));
}

foreach ($deals as $key => $value) {
  $id = $value['ID'];

  $currentTimesec = time();
  $currentDateTime = date('Y-m-d H:i:s', $currentTimesec);
  $newTimestamp = $currentTimesec + $delay; 
  $newDateTime = date('Y-m-d H:i:s', $newTimestamp);
  $datetime1 = new DateTime($currentDateTime);
  $datetime2 = new DateTime($newDateTime);
  $diff = $datetime2->getTimestamp() - $datetime1->getTimestamp();
  $minutes = round($diff / 60);
  mysqli_stmt_bind_param($stmt1, "isssssi", $id, $currentDateTime, $newDateTime, $minutes, $funnel_id, $stage_id, $prior);
  if (!mysqli_stmt_execute($stmt1)) {
    die("Ошибка выполнения запроса: " . mysqli_error($conn));
    mysqli_stmt_close($stmt1);
    mysqli_close($conn);
  }

}

mysqli_stmt_reset($stmt1);
mysqli_stmt_close($stmt1); 

  header('Location: index.php');
  exit;
}


?>
