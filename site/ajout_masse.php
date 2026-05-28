<?php

require "api/lib/bdd.php";
$pdo = db_connect();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json; charset=utf-8");

    $data = json_decode(file_get_contents("php://input"), true);

    $masse = $data["masse"] ?? null;
    $idLot = $data["id_lots"] ?? [];

    // Sécurité : masse obligatoire, numérique, max 2 décimales
    if ($masse === null || !preg_match('/^\d+(\.\d{1,2})?$/', $masse)) {
        echo json_encode([
            "success" => false,
            "message" => "Masse invalide. Elle doit contenir au maximum 2 décimales."
        ]);
        exit;
    }

    // Sécurité : lot obligatoire
    if(!is_array($idLot) || count($idLot) === 0) {
        echo json_encode([
            "success" => false,
            "message" => "Veuillez sélectionner au moins un lot."
        ]);
        exit;
    }

    foreach ($idLot as $idLots) {
        if (!ctype_digit((string)$idLots)) {
            echo json_encode([
                "success" => false,
                "message" => "Un des lots sélectionnés est invalide."
            ]);
            exit;
        }
    }

    try {
        $pdo->beginTransaction();
        
        // 1. Insérer une seule masse finale
        $stmt = $pdo->prepare("
            INSERT INTO masse (
                masse_masse,
                masse_dateHeure
            )
            VALUES (
                :masse,
                NOW()
            )        
        ");

        $stmt->execute([
            ":masse" => $masse
        ]);

        $idMasse = $pdo->lastInsertId();

        // 2. Associer cette même masse à tous les lots sélectionnés
        $placeholders = implode(",", array_fill(0, count($idLot), "?"));

        $stmt = $pdo->prepare("
            UPDATE lot
            SET id_masse = ?
            WHERE id_lot IN ($placeholders)
        ");

        $params = array_merge([$idMasse], $idLot);

        $stmt->execute($params);

        $pdo->commit();


        echo json_encode([
            "success" => true,
            "message" => "Masse ajoutée avec succès."
        ]);
        exit;

    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Erreur lors de l'enregistrement en base de données."
        ]);
        exit;
    }
}


// ======================================================
// RÉCUPÉRATION DES LOTS POUR AFFICHAGE
// ======================================================

try {
    $stmtLots = $pdo->query("
    SELECT
        lot.id_lot,
        variete.variete_nom,
        lot.lot_dateHeureEntree,
        lot.lot_dateHeureSortie
    FROM lot
    INNER JOIN variete ON lot.id_variete = variete.id_variete
    WHERE 
        lot.id_masse IS NULL
        AND lot.lot_dateHeureSortie IS NOT NULL
    ORDER BY lot.lot_dateHeureEntree DESC
    ");

    $lots = $stmtLots->fetchAll();

} catch (PDOException $e) {
    $lots = [];
}
?>

    <div class="export-page">

        <div class="compartiment">
            <div class="comp-label">Ajouter une masse produite</div>

            <div style="display: flex; gap: 2rem; align-items: flex-start;">

                <!-- LOTS À GAUCHE -->
                <div style="width: 280px;">
                    <div class="filter-name" style="margin-bottom: .5rem;">
                        Lot de houblon
                    </div>

                    <div class="type-list" id="listeLots">
                        <?php if (!empty($lots)): ?>

                            <?php foreach ($lots as $lot): ?>
                                <div class="type-item">
                                    <input
                                        type="checkbox"
                                        name="id_lots[]"
                                        value="<?= htmlspecialchars($lot["id_lot"]) ?>"
                                        autocomplete="off"
                                    >

                                    <span class="dot dot-green"></span>

                                    <span>
                                        Lot #<?= htmlspecialchars($lot['id_lot']) ?>
                                        -
                                        <?= htmlspecialchars($lot["variete_nom"]) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <div class="empty-state">
                                Aucun lot disponible.
                            </div>

                        <?php endif; ?>
                    </div>
                </div>

                <!-- MASSE À DROITE -->
                <div style="flex: 1;">
                    <div class="filter-group">
                        <label for="masseInput" class="filter-name">
                            Masse totale produite en kg
                        </label>

                        <input
                            type="text"
                            id="masseInput"
                            placeholder="Ex : 125.50"
                            autocomplete="off"
                        >
                    </div>

                    <div class="filter-actions" style="margin-top: 1rem;">
                        <button class="btn btn-search" id="btnAjouter">
                            Ajouter la masse
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

<script src="js/ajoutMasse/index.js"></script>