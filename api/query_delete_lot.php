<?php

header('Content-Type: application/json');

require "lib/bdd.php";

try
{
	$idLot = (int)$_POST['id_lot'];

	if ($idLot <= 0)
	{
		throw new Exception("Paramètres invalides");
	}

	$pdo = db_connect();
	$pdo->beginTransaction();

	// 1. supprimer liaison étage
	db_query(
		$pdo,
		"DELETE FROM lotEtage
		 WHERE id_lot = ?",
		[$idLot]
	);

	// 2. supprimer le lot
	db_query(
		$pdo,
		"DELETE FROM lot
		 WHERE id_lot = ?",
		[$idLot]
	);

	$pdo->commit();

	echo json_encode([
		"status" => "ok"
	]);
}
catch (Throwable $e)
{
	if (isset($pdo))
	{
		$pdo->rollBack();
	}

	http_response_code(500);

	echo json_encode([
		"status" => "error",
		"message" => $e->getMessage()
	]);
}

?>