<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try {
	$pdo = db_connect();

	$stmt = db_query(
		$pdo,
		"UPDATE etatSechoir
		SET 
		etatSechoir_ajoutMinute = etatSechoir_ajoutMinute + TIMESTAMPDIFF(MINUTE, etatSechoir_pauseDebut, NOW()),
		etatSechoir_pauseDebut = NULL,
		etatSechoir_status = 'en cours',
		etatSechoir_dataMaj = NOW()
		WHERE etatSechoir_pauseDebut IS NOT NULL
		AND etatSechoir_status = 'pause'"
	);

	if ($stmt->rowCount() === 0) {
		echo json_encode([
		"status" => "ignored",
		"message" => "Le séchoir doit être en pause pour pouvoir reprendre le séchage."
		]);
		exit;
	}

	db_query(
		$pdo,
		"UPDATE pause 
		SET pause_dateHeureFin = NOW()
		WHERE pause_dateHeureFin IS NULL"
	);

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
