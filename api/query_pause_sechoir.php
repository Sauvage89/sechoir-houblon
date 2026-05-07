<?php
header("Content-Type: application/json; charset=utf-8");

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=sechoir;charset=utf8mb4",
        "singe",
        "singe",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $stmt = $pdo->prepare("
        UPDATE etatSechoir
        SET 
            etatSechoir_status = 'pause',
            etatSechoir_pauseDebut = NOW(),
            etatSechoir_dataMaj = NOW()
        WHERE id_etatSechoir = 1
        AND etatSechoir_status != 'pause'
    ");

    $stmt->execute();

    if ($stmt->rowCount() === 0) {
    echo json_encode([
        "status" => "ignored",
        "message" => "Le séchoir est déjà en pause."
    ]);
    exit;
    }

    echo json_encode([
        "status" => "ok"
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
    exit;
}