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

    $data = json_decode(file_get_contents("php://input"), true);

    $session = $data["session"];
    $variete = $data["variete"];
    $etage   = $data["etage"] ?: null;

    $stmt = $pdo->prepare("
        INSERT INTO houb_sech
        (houb_sech_ses_sech, houb_sech_variete, houb_sech_etage, houb_sech_date_in)
        VALUES (?, ?, ?, NOW())
    ");

    $stmt->execute([$session, $variete, $etage]);

    echo json_encode(["status" => "ok"]);

} catch(PDOException $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}
?>