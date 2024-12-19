<?php

include_once('crest.php');


if(!empty($_REQUEST['auth']['application_token']) && $_REQUEST['auth']['application_token'] == '#######')
{
	if(in_array($_REQUEST['event'], ['0' => 'ONCRMDEALUPDATE', ]))
	{
		$result = CRest::call(
			'crm.deal.get',
			[
				'ID' => $_REQUEST['data']['FIELDS']['ID']
			]
		);
		$id = $result['result']['ID'];
		$id_stage = $result['result']['STAGE_ID'];
		$id_funnel = $result['result']['CATEGORY_ID'];
		$conn = mysqli_connect("localhost", "bitrix24", "Xzup2jWB", "reminderInTG");
		if (!$conn) {
		  die("Ошибка подготовки запроса: " . mysqli_error($conn));
		}
		
			$sql = "SELECT * FROM deals WHERE id_deal = $id";
			$result_deals = $conn->query($sql); // Получаем результат выборки
			$result_stage_id = $result_deals->fetch_assoc();
			if ($id_stage != $result_stage_id['stage']) {
				$stmt = $conn->prepare("UPDATE deals SET stage = ? WHERE id_deal = ?");
					$stmt->bind_param("si", $id_stage, $id);

					if ($stmt->execute()) {
    					echo "Стадия успешно обновлена.";
					} else {
    					echo "Ошибка обновления: " . $stmt->error;
						}

					}


			$sql1 = "SELECT * FROM reminders WHERE id_stage = '$id_stage'";
			$result1 = $conn->query($sql1);
			if ($result1->num_rows > 0) {
								$result_dealscol = $result1->fetch_assoc();
				$dealscol = $result_dealscol['dealscol'];
				$delay = ($result_dealscol['day'] * 24 * 60 * 60) + ($result_dealscol['hour'] * 60 * 60) + ($$result_dealscol['minut'] * 60);
				$sql2 = "SELECT * FROM deals WHERE id_deal = $id";
				$result2 = $conn->query($sql2);
				if ($result2->num_rows > 0) {
					$stmt1 = $conn->prepare("UPDATE deals SET dealscol = ? WHERE id_deal = ?");
						$stmt1->bind_param("ii", $result_dealscol['dealscol'], $id);
						if ($stmt1->execute()) {
							echo "Минуты переписаны.";
						} else {
							echo "Ошибка обновления: " . $stmt1->error;
							}
				} else {
					//Здесь сделка создается
					$stmt3 = mysqli_prepare($conn, "INSERT INTO deals (id_deal, date_start, date_end, dealscol, funnel, stage) VALUES (?, ?, ?, ?, ?, ?)");
					if (!$stmt3) {
						die("Ошибка подготовки запроса: " . mysqli_error($conn));
					  }
					  
					  
						$currentTimesec = time();
						$currentDateTime = date('Y-m-d H:i:s', $currentTimesec);
						$newTimestamp = $currentTimesec + $delay; 
						$newDateTime = date('Y-m-d H:i:s', $newTimestamp);
						mysqli_stmt_bind_param($stmt3, "isssss", $id, $currentDateTime, $newDateTime, $dealscol, $id_funnel, $id_stage);
						if (!mysqli_stmt_execute($stmt3)) {
						  die("Ошибка выполнения запроса: " . mysqli_error($conn));
						  mysqli_stmt_close($stmt3);
						  mysqli_close($conn);
						}
					}} else {
				$sql = "DELETE FROM deals WHERE id_deal = $id";
		  
				// Выполнение запроса
				if ($conn->query($sql) === TRUE) {
					echo "Данные успешно удалены";
				} else {
					echo "Ошибка при удалении данных: " . $conn->error;
				}
			}
			
			
			// Закрытие соединения
			

		// $dir = $_SERVER['DOCUMENT_ROOT'] . __DIR__ . '/tmp/'; try this depending on your server configuration
		$dir = 'tmp/';

		if(!file_exists($dir))
		{
			echo 'create: '.mkdir($dir, 0777, true);
		}

		// save event to log
		file_put_contents(
			$dir . time() . '_' . rand(1, 9999) . '.txt',
			var_export(
				[
					'result' => $result,
					'request' =>
						[
							'event' => $_REQUEST['event'],
							'data' => $_REQUEST['data'],
							'ts' => $_REQUEST['ts'],
							'auth' => $_REQUEST['auth'],
						],
						'res' => $result_stage_id['stage'],
						'sql' => $sql
				],
				true
			)
		);
		$conn->close();

	}
}
