<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try {
	$pdo = db_connect();

	$stmt = db_query(
		$pdo,
		"INSERT INTO etatSechoir (etatSechoir_status, etatSechoir_dataMaj, etatSechoir_ajoutMinute)
		SELECT 'en cours', NOW(), 0
		WHERE NOT EXISTS (
		SELECT 1
		FROM etatSechoir
      	WHERE etatSechoir_status IN ('en cours', 'pause')
		);"
	);

	if ($stmt->rowCount() === 0) {
		echo json_encode([
			"status" => "ignored",
			"message" => "Un cycle est déjà en cours ou en pause."
		]);
		exit;
	}

	echo json_encode([
		"status" => "ok"
	]);
}
catch (Throwable $e)
{
	http_response_code(500);

	echo json_encode([
		"status"  => "error",
		"message" => $e->getMessage()
	]);
}