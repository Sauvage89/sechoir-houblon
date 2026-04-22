<?php

header('Content-Type: application/json');

$data = [
    "rows" => [
        [
            "numero_lot"   => "LOT-027",
            "variete"      => "Aramis",
            "sechoir"      => "S1",
            "palier"       => "P3",
            "date_sechage" => "2026-04-18",
            "quantite_kg"  => 120,
            "duree_heures" => 14.5,
            "humidite_fin" => 9.8,
            "statut"       => "Terminé"
        ],
        [
            "numero_lot"   => "LOT-028",
            "variete"      => "Magnum",
            "sechoir"      => "S2",
            "palier"       => "P2",
            "date_sechage" => "2026-04-19",
            "quantite_kg"  => 95,
            "duree_heures" => 12.0,
            "humidite_fin" => 10.3,
            "statut"       => "En cours"
        ],
	[
            "numero_lot"   => "LOT-030",
            "variete"      => "Magnum",
            "sechoir"      => "S2",
            "palier"       => "P2",
            "date_sechage" => "2026-04-19",
            "quantite_kg"  => 95,
            "duree_heures" => 12.0,
            "humidite_fin" => 10.3,
            "statut"       => "En cours"
        ],
        [
            "numero_lot"   => "LOT-029",
            "variete"      => "Strisselspalt",
            "sechoir"      => "S1",
            "palier"       => "P4",
            "date_sechage" => "2026-04-20",
            "quantite_kg"  => 140,
            "duree_heures" => 16.2,
            "humidite_fin" => 8.9,
            "statut"       => "Erreur"
        ]
    ],
    "count" => 3
];

echo json_encode($data);