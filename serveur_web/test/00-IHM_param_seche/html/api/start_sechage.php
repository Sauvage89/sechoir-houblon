<?php

$host = "localhost";
$db   = "sechoir_houblon";
$user = "www-data";
$pass = "password";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->prepare("INSERT INTO ses_sech (ses_sech_date_debut) VALUES (NOW())");
    $stmt->execute();

    // récupérer l'ID du cycle créé
    $id = $pdo->lastInsertId();

    echo json_encode(["status" => "ok"]);

} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

?>