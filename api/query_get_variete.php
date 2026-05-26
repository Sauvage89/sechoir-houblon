<?php

// Librairie de connexion et de requête a une base de donnée
require "lib/bdd.php";

try {
	$pdo = db_connect();

	$stmt = db_query(
		$pdo,
		"SELECT id_variete, variete_nom FROM variete WHERE variete_actif = ?",
		[1]
	);

	// Récupere les lignes sous le format (id_var, nom_var)
	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// envoie des donnée en format JSON
	echo json_encode($data);
} catch (Exception $e) {
	http_response_code(500);
	echo json_encode([
		"error" => $e->getMessage()
	]);
}