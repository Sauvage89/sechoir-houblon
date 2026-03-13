<?php
header('Content-Type: application/json');

$host = "localhost";
$db   = "sechoir_houblon";
$user = "www-data";
$pass = "password";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Connexion BDD échouée']);
    exit;
}

// Récupère la dernière session
$stmt = $pdo->query("SELECT * FROM ses_sech ORDER BY id_ses_sech DESC LIMIT 1");
$lastSession = $stmt->fetch(PDO::FETCH_ASSOC);

if ($lastSession) {
    echo json_encode(['status' => 'ok', 'lastSession' => $lastSession]);
} else {
    echo json_encode(['status' => 'ok', 'lastSession' => null]);
}