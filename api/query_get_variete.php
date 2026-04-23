<?php
function query_get_variete(): array
{
	require "api/lib/bdd.php";

	$pdo = db_connect();

	$stmt = db_query(
		$pdo,
		"SELECT id_variete, variete_nom FROM variete WHERE variete_actif = ?",
		[1]
	);

	return ($stmt->fetchAll());
}
?>