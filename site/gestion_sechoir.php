<?php
include '../api/get_status.php';
$data = json_decode($json, true);

$etat_cycle = $data['etat_cycle'] ?? '--';
$derniere_alerte = $data['derniere_alerte'] ?? '--';
$etages = $data['etages'] ?? [];

function getEtage($etages, $numero) {
  foreach ($etages as $e) {
    if ($e['etage'] == $numero) return $e;
  }
  return ['variete' => '--', 'date_debut' => '--', 'date_fin' => '--'];
}

$e1 = getEtage($etages, 1);
$e2 = getEtage($etages, 2);
$e3 = getEtage($etages, 3);
$e4 = getEtage($etages, 4);
?>

<!-- ══ ÉTAT DU CYCLE ══ -->
<section class="mb-3">
  <h2 class="section-title">
    État du cycle
  </h2>
  
  <div class="floor-card">
    <div class="row g-2">

      <div class="col-12 col-md-6">
        <div class="info-block">
          <div class="info-label">🔄 État</div>
          <div class="info-value" id="etat_cycle"><?= htmlspecialchars($etat_cycle) ?></div>
        </div>
      </div>

      <div class="col-12 col-md-6">
        <div class="info-block">
          <div class="info-label">⚠️ Dernière alerte</div>
          <div class="info-value" id="derniere_alerte"><?= htmlspecialchars($derniere_alerte) ?></div>
        </div>
      </div>

    </div>
  </div>
</section>



<!-- ══ ÉTAGE 4 (haut) ══ -->
<section class="mb-3">
  <h2 class="section-title">
    <span class="section-num">04</span>
    Étage 4 — Niveau haut
  </h2>

  <div class="floor-card">
    <div class="row g-2">

      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🌿 Variété</div>
          <div class="info-value" id="variete-4"><?= htmlspecialchars($e4['variete']) ?></div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕐 Début</div>
          <div class="info-value" id="debut-4"><?= htmlspecialchars($e4['date_debut']) ?></div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕔 Fin prévue</div>
          <div class="info-value" id="fin-4"><?= $e4['date_fin'] ?? 'En cours' ?></div>
        </div>
      </div>
      
    </div>
  </div>
</section>



<!-- ══ ÉTAGE 3 ══ -->
<section class="mb-3">
  <h2 class="section-title">
    <span class="section-num">03</span>
    Étage 3 — Niveau moyen
  </h2>

  <div class="floor-card">
    <div class="row g-2">

      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🌿 Variété</div>
          <div class="info-value" id="variete-3"><?= htmlspecialchars($e3['variete']) ?></div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕐 Début</div>
          <div class="info-value" id="debut-3"><?= htmlspecialchars($e3['date_debut']) ?></div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕔 Fin prévue</div>
          <div class="info-value" id="fin-3"><?= $e3['date_fin'] ?? 'En cours' ?></div>
        </div>
      </div>

    </div>
  </div>
</section>



