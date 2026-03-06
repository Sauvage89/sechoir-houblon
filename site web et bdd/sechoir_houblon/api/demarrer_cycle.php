<?php
header('Content-Type: application/json');
require_once("../config/database.php");

try {
    $sql = "
        INSERT INTO cycles_sechage (date_debut, etat)
        VALUES (NOW(), '1')
    ";

    $pdo->exec($sql);

    echo json_encode([
        "status" => "ok",
        "message" => "Cycle démarré"
    ]);
} catch(PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
    
}