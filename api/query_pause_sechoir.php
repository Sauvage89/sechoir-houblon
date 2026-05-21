<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try {
	$pdo = db_connect();
	$stmt = db_query(
		$pdo,
		"UPDATE etatSechoir
		SET etatSechoir_status = 'pause', etatSechoir_pauseDebut = NOW(), etatSechoir_dataMaj = NOW()
		WHERE id_etatSechoir = 1 AND etatSechoir_status != 'pause'"
	);


	if ($stmt->rowCount() === 0) {
	echo json_encode([
		"status" => "ignored",
		"message" => "Le séchoir est déjà en pause."
	]);
	exit;
	}

	echo json_encode([
		"status" => "ok"
	]);
	exit;

} catch (Throwable $e) {
	echo json_encode([
		"status" => "error",
		"message" => $e->getMessage()
	]);
	exit;
}