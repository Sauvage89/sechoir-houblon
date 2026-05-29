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

	var valeurs = data.debug;

	for (var i = 0; i < valeurs.length; i++)
	{
		var temperature = parseFloat(valeurs[i].temperature_valeur);

		var doc_capteur_temp = document.getElementById("capteur_" + (i + 1));

		if (doc_capteur_temp)
		{
			doc_capteur_temp.textContent = temperature.toFixed(1);
		}
	}

	updateTemperatureMoy(valeurs);
}

function updateTemperatureMoy(valeurs) {

	var moyenne = document.getElementById("moyenne");

	if (!moyenne) return;

	if (valeurs.length === 0)
	{
		moyenne.textContent = "-- °C";
		return;
	}

	var somme = 0;

	for (var i = 0; i < valeurs.length; i++)
	{
		somme += parseFloat(valeurs[i].temperature_valeur);
	}

	var moy = somme / valeurs.length;

	moyenne.textContent = moy.toFixed(1) + " °C";
}

async function rafraichirStatus() {
	await fetch("../api/get_status.php")
		.then(r => r.json())
		.then(data => {
			updateEtatCycle(data);
			updateEtage(data);
			if (typeof setPauseButtonsState === 'function') {
				try {
					setPauseButtonsState(data.etat_cycle);
				} catch (e) {
					console.error('Erreur en appliquant setPauseButtonsState:', e);
				}
			}
		});

	const response = await fetch("../api/get_temperature.php");
	const data = await response.json();

	console.log(data);
	
	updateTemperature(data);
	
}

