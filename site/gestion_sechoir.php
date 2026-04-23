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

<?php include "gestion_sechoir_overlay.php"; ?>

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

      <div class="col-12 col-md-4 info-block">
        <div class="info-label">🕔 Fin prévue</div>
        <div class="info-value" id="fin-4"><?= $e4['date_fin'] ?? 'En cours' ?></div>
      </div>

      <div class="col-12 col-md-4 btn-block">
        <button onclick="showOverlay(4)">Étage 4</button>
        <button onclick="descendre(4)">↓</button>
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

      <div class="col-12 col-md-4 btn-block">
        <button onclick="showOverlay(3)">Étage 3</button>
        <button onclick="descendre(3)">↓</button>
      </div>
  
    </div>
  </div>
</section>

<!-- ══ ÉTAGE 2 ══ -->
<section class="mb-3">
  <h2 class="section-title">
    <span class="section-num">02</span>
    Étage 2 — Niveau bas
  </h2>

  <div class="floor-card">
    <div class="row g-2">
      
      <div class="col-12 col-md-4">
      	<div class="info-block">
          <div class="info-label">🌿 Variété</div>
          <div class="info-value" id="variete-2"><?= htmlspecialchars($e2['variete']) ?></div>
        </div>
      </div>
      
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕐 Début</div>
          <div class="info-value" id="debut-2"><?= htmlspecialchars($e2['date_debut']) ?></div>
        </div>
      </div>
      
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕔 Fin prévue</div>
          <div class="info-value" id="fin-2"><?= $e2['date_fin'] ?? 'En cours' ?></div>
        </div>
      </div>
    
      <div class="col-12 col-md-4 btn-block">
        <button onclick="showOverlay(2)">Étage 2</button>
        <button onclick="descendre(2)">↓</button>
      </div>
  
    </div>
  </div>
</section>

<!-- ══ ÉTAGE 1 (bas) — avec capteurs ══ -->
<section class="mb-3">
  <h2 class="section-title">
    <span class="section-num">01</span>
    Étage 1 — Niveau tiroir
  </h2>
  
  <div class="floor-card floor-card--sensors">

    <div class="row g-2 mb-2">
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🌿 Variété</div>
          <div class="info-value" id="variete-1"><?= htmlspecialchars($e1['variete']) ?></div>
        </div>
      </div>
      
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕐 Début</div>
          <div class="info-value" id="debut-1"><?= htmlspecialchars($e1['date_debut']) ?></div>
        </div>
      </div>
      
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕔 Fin prévue</div>
          <div class="info-value" id="fin-1"><?= $e1['date_fin'] ?? 'En cours' ?></div>
        </div>
      </div>
    </div>

    <!-- Température moyenne -->
    <div class="row g-2 mb-2">
      <div class="col-12">
        <div class="info-block info-block--moyenne">
          <div class="info-label">🌡️ Température moyenne des capteurs</div>
          <div class="info-value" id="moyenne">-- °C</div>
        </div>
      </div>
    </div>

    <!-- 6 capteurs : 2 col mobile / 3 col tablette / 6 col desktop -->
    <div class="row g-2">
      <?php for ($i = 1; $i <= 6; $i++): ?>
      <div class="col-6 col-md-4 col-lg-2">
        <div class="sensor-card" id="card-<?= $i ?>">
          <div class="sensor-label">Capteur <?= $i ?></div>
          <div class="sensor-temp"><span id="capteur_<?= $i ?>">--</span><span class="unit">°C</span></div>
          <div class="sensor-sub" id="sub-<?= $i ?>">Chargement...</div>
          <div class="sensor-bar"><div class="sensor-bar-fill" id="bar-<?= $i ?>" style="width:0%"></div></div>
        </div>
      </div>
      <?php endfor; ?>
    </div>

    
    <div class="col-12 col-md-4 btn-block">
      <button onclick="showOverlay(1)">Étage 1</button>
      <button onclick="retirer()">Retirer le houblon</button>
    </div>
  
  </div>
</section>