<?php

header('Content-Type: application/json');

$temperatures = [];

for ($id_capteur = 0; $id_capteur < 6; $id_capteur++) {

	$cmd = "cd /usr/local/bin/programme/sauvage && ./get_temperature $id_capteur 2>&1";

	$output = shell_exec($cmd);

	$temperatures[] = [
		"temperature_valeur" => $output
	];
}

echo json_encode([
	"status" => "ok",
	"debug" => $temperatures
]);
