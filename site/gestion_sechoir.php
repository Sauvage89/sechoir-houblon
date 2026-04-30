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

function updateEtatCycle(data) {
	document.getElementById("etat_cycle").textContent = data.etat_cycle ?? "—";
	document.getElementById("derniere_alerte").textContent = data.derniere_alerte ?? "—";
}

function updateEtage(data) {
	var etageMap;
	var etage;
	var estActif;
	var docVariete;
	var docDateDebut;
	var docDateFin;

	etageMap = updateEtagePrepareEtageMap(data);
	for (let num = 1; num <= 4; num++) {
		etage = etageMap[num];
		estActif = (etage !== undefined);
		docVariete = document.getElementById("variete-" + num);
		docDateDebut = document.getElementById("debut-" + num);
		docDateFin = document.getElementById("fin-" + num);
		if (estActif) {
			docVariete.textContent = (etage.variete !== null && etage.variete !== undefined) ? etage.variete : "—";
			docDateDebut.textContent = (etage.date_debut !== null && etage.date_debut !== undefined) ? etage.date_debut : "—";
			docDateFin.textContent = (etage.date_fin !== null && etage.date_fin !== undefined) ? etage.date_fin : "—";
		} else {
			docVariete.textContent = "—";
			docDateDebut.textContent = "—";
			docDateFin.textContent = "—";
		}
	}
}

function updateEtagePrepareEtageMap(data) {
	var etageMap;
	var etages;
	var etage;
	var num;

	etageMap = {};
	etages = data.etages ? data.etages : [];
	for (var i = 0; i < etages.length; i++) {
		etage = etages[i];
		num = parseInt(etage.etage, 10);
		etageMap[num] = etage;
	}
	return (etageMap);
}

function updateTemperature(data) {
	var capteurs = data.temperatures !== undefined ?? [] ? data.temperatures : [];
	var valeurs = [];
	var i;
	var cap;
	var valeur;
	var heure;

	for (i = 0; i < capteurs.length; i++) {
		cap = capteurs[i];
		var index = i + 1;
		valeur = (cap.temperature_valeur !== null && cap.temperature_valeur !== undefined)
			? parseFloat(cap.temperature_valeur)
			: null;
		heure = (cap.temperature_dateHeure !== undefined)
			? cap.temperature_dateHeure
			: null;

		// ── Température ───────────────────────────────
		var spanTemp = document.getElementById("capteur_" + index);
		if (spanTemp !== null) {
			spanTemp.textContent = (valeur !== null)
			? valeur.toFixed(1)
			: "--";
		}

		// ── Sous-titre (heure) ───────────────────────
		var sub = document.getElementById("sub-" + index);
		if (sub !== null) {
			if (heure) {
			sub.textContent = heure.slice(11, 16);
			} else {
			sub.textContent = "Aucune donnée";
			}
		}

		// ── Barre (15°C / 50°C) ──────────────────────
		var bar = document.getElementById("bar-" + index);
		if (bar !== null && valeur !== null) {

			var pct = ((valeur - 15) / (50 - 15)) * 100;

			if (pct < 0) pct = 0;
			if (pct > 100) pct = 100;

			bar.style.width = pct + "%";
		}

		// ── Stock valeurs ─────────────────────────────
		if (valeur !== null) {
			valeurs.push(valeur);
		}
	}
	updateTemperatureMoy(valeurs);
}

function updateTemperatureMoy(valeurs) {
	var moyenne = document.getElementById("moyenne");

	if (moyenne !== null) {

	if (valeurs.length > 0) {

		var i;
		var somme = 0;
		var moy;

		for (i = 0; i < valeurs.length; i++) {
		somme = somme + valeurs[i];
		}

		moy = somme / valeurs.length;

		moyenne.textContent = moy.toFixed(1) + " °C";

	} else {

		moyenne.textContent = "-- °C";
	}
	}
}

async function rafraichirStatus() {
	await fetch("../api/get_status.php")
		.then(r => {
		if (!r.ok) throw new Error("HTTP " + r.status);
		return r.json();
	})
	.then(data => {
		updateEtatCycle(data);
		updateEtage(data);
		updateTemperature(data);
	})
}

rafraichirStatus();
</script>