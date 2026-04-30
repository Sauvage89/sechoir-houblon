<?php include "gestion_sechoir_overlay.php"; ?>

<!-- ══ ÉTAT DU CYCLE ══ -->
<section class="mb-3">
  <h2 class="section-title">État du cycle</h2>
  <div class="floor-card">
    <div class="row g-2">
      <div class="col-12 col-md-6">
        <div class="info-block">
          <div class="info-label">🔄 État</div>
          <div class="info-value" id="etat_cycle">Chargement...</div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="info-block">
          <div class="info-label">⚠️ Dernière alerte</div>
          <div class="info-value" id="derniere_alerte">Chargement...</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ ÉTAGE 4 ══ -->
<section class="mb-3">
  <h2 class="section-title"><span class="section-num">04</span> Étage 4 — Niveau haut</h2>
  <div class="floor-card">
    <div class="row g-2">
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🌿 Variété</div>
          <div class="info-value" id="variete-4">—</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕐 Début</div>
          <div class="info-value" id="debut-4">—</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕔 Fin prévue</div>
          <div class="info-value" id="fin-4">—</div>
        </div>
      </div>
      <div class="btn-block">
        <div class="btn-row">
          <button onclick="showOverlay(4)">Étage 4</button>
          <button onclick="descendre(4, event)">↓</button>
        </div>
        <p class="lot-warning"></p>
      </div>
    </div>
  </div>
</section>

<!-- ══ ÉTAGE 3 ══ -->
<section class="mb-3">
  <h2 class="section-title"><span class="section-num">03</span> Étage 3 — Niveau moyen</h2>
  <div class="floor-card">
    <div class="row g-2">
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🌿 Variété</div>
          <div class="info-value" id="variete-3">—</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕐 Début</div>
          <div class="info-value" id="debut-3">—</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕔 Fin prévue</div>
          <div class="info-value" id="fin-3">—</div>
        </div>
      </div>
      <div class="btn-block">
        <div class="btn-row">
          <button onclick="showOverlay(3)">Étage 3</button>
          <button onclick="descendre(3, event)">↓</button>
        </div>
        <p class="lot-warning"></p>
      </div>
    </div>
  </div>
</section>

<!-- ══ ÉTAGE 2 ══ -->
<section class="mb-3">
  <h2 class="section-title"><span class="section-num">02</span> Étage 2 — Niveau bas</h2>
  <div class="floor-card">
    <div class="row g-2">
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🌿 Variété</div>
          <div class="info-value" id="variete-2">—</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕐 Début</div>
          <div class="info-value" id="debut-2">—</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕔 Fin prévue</div>
          <div class="info-value" id="fin-2">—</div>
        </div>
      </div>
      <div class="btn-block">
        <div class="btn-row">
          <button onclick="showOverlay(2)">Étage 2</button>
          <button onclick="descendre(2, event)">↓</button>
        </div>
        <p class="lot-warning"></p>
      </div>
    </div>
  </div>
</section>

<!-- ══ ÉTAGE 1 — avec capteurs ══ -->
<section class="mb-3">
  <h2 class="section-title"><span class="section-num">01</span> Étage 1 — Niveau tiroir</h2>
  <div class="floor-card floor-card--sensors">

    <div class="row g-2 mb-2">
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🌿 Variété</div>
          <div class="info-value" id="variete-1">—</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕐 Début</div>
          <div class="info-value" id="debut-1">—</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="info-block">
          <div class="info-label">🕔 Fin prévue</div>
          <div class="info-value" id="fin-1">—</div>
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

    <!-- 6 capteurs -->
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
      <button onclick="retirer(1)">Retirer le houblon</button>
      <p id="lot-warning" class="lot-warning"></p>
    </div>

  </div>
</section>

<script>
async function rafraichirStatus() {
  await fetch("../api/get_status.php")
    .then(r => {
      if (!r.ok) throw new Error("HTTP " + r.status);
      return r.json();
    })
    .then(data => {
      console.log(data);

      document.getElementById("etat_cycle").textContent     = data.etat_cycle     ?? "—";
      document.getElementById("derniere_alerte").textContent = data.derniere_alerte ?? "—";

      const etageMap = {};
      (data.etages ?? []).forEach(e => {
        etageMap[parseInt(e.etage)] = e;
      });

      [1, 2, 3, 4].forEach(num => {
	const e         = etageMap[num];        // undefined si étage vide
	const estActif  = e !== undefined;

	document.getElementById(`variete-${num}`).textContent = estActif ? (e.variete    ?? "—") : "—";
	document.getElementById(`debut-${num}`).textContent   = estActif ? (e.date_debut ?? "—") : "—";
	document.getElementById(`fin-${num}`).textContent     = estActif ? (e.date_fin   ?? "—") : "—";
	});

      // ── Capteurs ───────────────────────────────────────────
      const capteurs = data.temperatures ?? [];
      const valeurs  = [];

      capteurs.forEach((cap, index) => {
        const i     = index + 1;
        const valeur = cap.temperature_valeur !== null ? parseFloat(cap.temperature_valeur) : null;
        const heure  = cap.temperature_dateHeure ?? null;

        // Température
        const spanTemp = document.getElementById(`capteur_${i}`);
        if (spanTemp) spanTemp.textContent = valeur !== null ? valeur.toFixed(1) : "--";

        // Sous-titre (heure)
        const sub = document.getElementById(`sub-${i}`);
        if (sub) sub.textContent = heure ? heure.slice(11, 16) : "Aucune donnée";

        // Barre (min 15°C / max 50°C)
        const bar = document.getElementById(`bar-${i}`);
        if (bar && valeur !== null) {
          const pct = Math.min(100, Math.max(0, ((valeur - 15) / (50 - 15)) * 100));
          bar.style.width = pct + "%";
        }

        if (valeur !== null) valeurs.push(valeur);
      });

      // Moyenne
      const moyenne = document.getElementById("moyenne");
      if (moyenne) {
        if (valeurs.length > 0) {
          const moy = valeurs.reduce((a, b) => a + b, 0) / valeurs.length;
          moyenne.textContent = moy.toFixed(1) + " °C";
        } else {
          moyenne.textContent = "-- °C";
        }
      }
    })
    .catch(error => {
      console.error("Erreur API :", error);
      document.getElementById("etat_cycle").textContent     = "Erreur de chargement";
      document.getElementById("derniere_alerte").textContent = "Erreur de chargement";
    });
}

rafraichirStatus();
</script>