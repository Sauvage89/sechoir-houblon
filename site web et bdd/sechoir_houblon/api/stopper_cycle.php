<?php

header('Content-Type: application/json');

require_once("../config/database.php");

try {
    $sql ="
        UPDATE cycles_sechage
        SET 
            date_fin = NOW(),
            etat = '0'
        WHERE etat = '1'
    ";

    $pdo->exec($sql);

    echo json_encode([
        "status" => "ok",
        "message" => "Cycle arrêté"
    ]);
} catch(PDOException $e) {
    echo json_encode([
        "satatus" => "error",
        "message" => $e->getMessage()
    ]);
}