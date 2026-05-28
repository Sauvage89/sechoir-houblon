<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try {
	$pdo = db_connect();

	$stmt = db_query(
		$pdo,
		"UPDATE etatSechoir
		SET etatSechoir_status = 'terminé', 
		etatSechoir_pauseDebut = NULL,
		etatSechoir_dataMaj = NOW()
		WHERE etatSechoir_status = 'en cours'"
	);

	if ($stmt->rowCount() === 0) {
		echo json_encode([
			"status" => "ignored",
			"message" => "Aucun séchoir n'est actuellement en cours pour pouvoir le terminer."
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