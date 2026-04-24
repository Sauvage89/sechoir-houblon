<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require "lib/bdd.php";

try
{
	$idLot = (int)$_POST['id_lot'];
	$idVariete = (int)$_POST['variete'];
	$remplissage = (int)$_POST['remplissage'];
	$tempsTheorique = (int)$_POST['temps_theorique'];

	if ($idLot <= 0) throw new Exception("id_lot invalide");
	if ($idVariete <= 0) throw new Exception("variete invalide");

	$pdo = db_connect();

	db_query(
		$pdo,
		"UPDATE lot
		 SET lot_remplissage = ?,
		     id_variete = ?,
		     lot_dureeTheorique = ?
		 WHERE id_lot = ?",
		[
			$remplissage,
			$idVariete,
			$tempsTheorique,
			$idLot
		]
	);

	echo json_encode([
		"status" => "ok"
	]);
}
catch (Throwable $e)
{
	http_response_code(500);

	echo json_encode([
		"status" => "error",
		"message" => $e->getMessage()
	]);
}
?>