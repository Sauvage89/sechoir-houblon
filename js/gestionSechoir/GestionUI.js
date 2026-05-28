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
		.then(r => r.json())
		.then(data => {
			updateEtatCycle(data);
			updateEtage(data);
			updateTemperature(data);
			if (typeof setPauseButtonsState === 'function') {
				try {
					setPauseButtonsState(data.etat_cycle);
				} catch (e) {
					console.error('Erreur en appliquant setPauseButtonsState:', e);
				}
			}
		});
}