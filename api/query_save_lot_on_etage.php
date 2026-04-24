<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require "lib/bdd.php";

$pdo = null;

try
{
	$idEtage = (int)$_POST['etage'];
	$idVariete = (int)$_POST['variete'];
	$remplissage = (int)$_POST['remplissage'];
	$tempsTheorique = (int)$_POST['temps_theorique'];
	

	$pdo = db_connect();
	$pdo->beginTransaction();

	// 1. créer le lot
	$stmt = db_query(
		$pdo,
		"INSERT INTO lot (
			lot_remplissage,
			lot_dateHeureEntree,
			lot_dureeTheorique,
			lot_actif,
			id_variete
		)
		VALUES (?, NOW(), ?, ?, ?)",
		[
			$remplissage,
			$tempsTheorique,
			1,
			$idVariete
		]
	);

	$idLot = $pdo->lastInsertId();

	// 2. affecter le lot à l’étage
	db_query(
		$pdo,
		"INSERT INTO lotEtage (
			id_lot,
			id_etage,
			lotEtage_dateDebut,
			lotEtage_dateFin
		)
		VALUES (?, ?, NOW(), NULL)",
		[
			$idLot,
			$idEtage
		]
	);

	$pdo->commit();

	echo json_encode([
		"status" => "ok",
		"id_lot" => $idLot
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