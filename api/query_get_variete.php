<?php
header('Content-Type: application/json');

require_once __DIR__ . "/lib/bdd.php";

try {
	$pdo = db_connect();

	$stmt = db_query(
		$pdo,
		"SELECT id_variete, variete_nom FROM variete WHERE variete_actif = ?",
		[1]
	);

	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($data);
}
catch (Exception $e) {
	http_response_code(500);
	echo json_encode([
		"error" => $e->getMessage()
	]);
}