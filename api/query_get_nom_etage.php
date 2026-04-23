<?php
require "lib/bdd.php";

$idEtage = (int)$_GET['etage'];

$pdo = db_connect();
$stmt = db_query(
	$pdo,
	"SELECT l.id_lot
	 FROM lotEtage le
	 JOIN lot l ON l.id_lot = le.id_lot
	 WHERE le.id_etage = ?
	 AND le.lotEtage_dateFin IS NULL
	 LIMIT 1",
	[$idEtage]
);

$row = $stmt->fetch();

echo json_encode([
	"nom" => $row ? "LOT_" . $row['id_lot'] : null
]);

?>