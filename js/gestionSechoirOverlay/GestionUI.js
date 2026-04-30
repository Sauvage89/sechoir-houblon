
function handleLot()
{
	if (LOT)
	{
		updateLot();
	}
	else
	{
		saveNewLot();
	}
}

async function updateLot() {
	const VARIETE = document.getElementById("inputVariete").value;
	const REMPLISSAGE = document.getElementById("remplissageVal").textContent;
  	const TEMPSTHEO = document.getElementById("temps-theorique").value;

	// conversion
	const tempsTheorique = parseTime(TEMPSTHEO);

	document.getElementById("id-lot").textContent = "Chargement";
	const res = await fetch("../api/query_update_lot_on_etage.php",
	{
		method: "POST",
		headers:
		{
			"Content-Type": "application/x-www-form-urlencoded"
		},
		body: new URLSearchParams(
		{
			id_lot: LOT.id_lot,
			variete: VARIETE,
			remplissage: REMPLISSAGE,
			temps_theorique: tempsTheorique
		})
	});
	const data = await res.json();

	if (data.status === "ok") {
		set_config_lot();
	} else {
		console.error(data);
	}
}

async function saveNewLot() {
	if (!ID_ETAGE) return;

	const VARIETE = document.getElementById("inputVariete").value;
	const REMPLISSAGE = document.getElementById("remplissageVal").textContent;
	const TEMPSTHEO = document.getElementById("temps-theorique").value;

	// conversion
	const varieteId = parseInt(VARIETE, 10);
	const remplissage = parseInt(REMPLISSAGE, 10);
	const tempsTheorique = parseTime(TEMPSTHEO);

	// validation simple
	if (!varieteId || varieteId <= 0) {
		showVarieteMessage("Veuillez sélectionner une variété");
		return;
	}
	if (Number.isNaN(remplissage)) {
		console.error("Remplissage invalide");
		return;
	}

	document.getElementById("id-lot").textContent = "Chargement";
	const res = await fetch("../api/query_save_lot_on_etage.php",
	{
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded"
		},
		body: new URLSearchParams ({
			etage: ID_ETAGE,
			variete: varieteId,
			remplissage: remplissage,
			temps_theorique: tempsTheorique
		})
	});
	const data = await res.json();
	if (data.status == "ok")
		set_config_lot();
}

function showVarieteMessage(text, isError = true) {
	const msg = document.getElementById("variete-msg");

	msg.textContent = text;
	msg.style.color = isError ? "red" : "green";
	msg.classList.add("show");

	setTimeout(() => {
		msg.classList.remove("show");
	}, 5000);
}

async function deleteLot() {
	if (!ID_ETAGE || !LOT) return;

	const res = await fetch("../api/query_delete_lot.php",
	{
		method: "POST",
		headers:
		{
			"Content-Type": "application/x-www-form-urlencoded"
		},
		body: new URLSearchParams(
		{
			id_lot: LOT.id_lot
		})
	});
  	const data = await res.json();

	if (data.status === "ok") {
		set_config_lot(); // refresh UI
	}
	else {
		console.error(data.message);
	}
}


function  ajustRemplisage(nb) {
	const doc = document.getElementById("remplissageVal");
	let valeur = parseInt(doc.textContent, 10);

	if (valeur >= 100 && nb === 10)
	return;
	if (valeur <= 0 && nb === -10)
	return;
	valeur += nb;
	doc.textContent = valeur;
}






async function retirer(idEtage)
{
	if (!idEtage) return;

	const res = await fetch("../api/query_retirer_lot.php",
	{
		method: "POST",
		headers:
		{
			"Content-Type": "application/x-www-form-urlencoded"
		},
		body: new URLSearchParams(
		{
			etage: idEtage
		})
	});

	const data = await res.json();

	if (data.status === "ok")
	{
		console.log("Lot retiré");
		set_config_lot();
	}
	else
	{
		console.error(data.message);
	}
	rafraichirStatus();
}

async function descendre(idEtage, event)
{
	if (!idEtage) return;
	const btn = event.target;

	const res = await fetch("../api/query_descendre_lot.php",
	{
		method: "POST",
		headers:
		{
			"Content-Type": "application/x-www-form-urlencoded"
		},
		body: new URLSearchParams(
		{
			etage: idEtage
		})
	});

	const data = await res.json();

	if (data.status === "ok")
	{
		console.log("Lot descendu");
		set_config_lot();
	}
	else {
		console.error(data.message);
    		showDescenteError(btn, data.message);
	}
	rafraichirStatus();
}

function showDescenteError(btn, message)
{
	const block = btn.closest(".btn-block");
	const msg = block.querySelector(".lot-warning");

	btn.classList.add("btn-error-flash");

	setTimeout(() => {
		btn.classList.remove("btn-error-flash");
	}, 600);

	msg.textContent = msg.textContent = message;
	msg.classList.add("show");
	setTimeout(() => {
		msg.classList.remove("show");
	}, 4000);
}

function showOverlay(idEtage) {
	ID_ETAGE = idEtage;
	document.getElementById("overlayTitle").textContent = "Étage " + idEtage;
	document.getElementById("overlay").style.display = "flex";
	set_config_lot();
}

function hideOverlay() {
	document.getElementById("overlay").style.display = "none";
	rafraichirStatus();
}

async function set_config_lot() {
	if (!ID_ETAGE) return;

	LOT = await get_lot(ID_ETAGE);
	const doc_info_lot = document.getElementById("info-lot");
	const doc_id_lot = document.getElementById("id-lot");
	const doc_remplissageVal = document.getElementById("remplissageVal");
	const doc_variete = document.getElementById("inputVariete");
	const doc_btn_save = document.getElementById("btn-lot-save");
	const doc_temps_theorique = document.getElementById("temps-theorique");

	if (LOT) {
		doc_info_lot.textContent = "Cette étage contient un lot";
		doc_id_lot.textContent = "LOT_" + LOT.id_lot;
		doc_info_lot.classList.remove("info-lot--empty");
		doc_info_lot.classList.add("info-lot--active");
		doc_remplissageVal.textContent = LOT.lot_remplissage;
		doc_variete.value = LOT.id_variete;
		doc_btn_save.textContent = "Sauvegarder le lot";
				doc_btn_save.classList.add("btn-save");
		console.log("dans set_config_lot le lot duree brut");
		console.log((LOT.lot_dureeTheorique));
		console.log("dans set_config_lot le lot duree formater");
		console.log(formatTime(LOT.lot_dureeTheorique));
		console.log(doc_temps_theorique);
		doc_temps_theorique.value = formatTime(LOT.lot_dureeTheorique);
	} else {
		doc_info_lot.textContent = "Cette étage ne contient pas de lot";
		doc_id_lot.textContent = "...";
		doc_info_lot.classList.remove("info-lot--active");
		doc_info_lot.classList.add("info-lot--empty");
		doc_variete.value = "";
		doc_remplissageVal.textContent = 50;
		doc_btn_save.textContent = "Créer un lot";
		doc_btn_save.classList.remove("btn-save");
		doc_temps_theorique.value = formatTime(0);
	}
}







async function get_variete() {
	const select = document.getElementById("inputVariete");
	if (!select) return;
	if (select.dataset.loaded === "true") return;
	try {
		const res = await fetch("../api/query_get_variete.php");
		const data = await res.json();
		if (!Array.isArray(data)) return;
		data.forEach(v => {
		const opt = document.createElement("option");
		opt.value = v.id_variete;
		opt.textContent = v.variete_nom;
		select.appendChild(opt);
		});
		select.dataset.loaded = "true";
	} catch (e) {
		console.error("Erreur chargement variétés", e);
	}
}



// --------------------------- TEMPS THEORIQUE --------START---------------------------------


// Convertie le format (HH:MM)/minute en minute
function parseTime(value) {
  value = value.trim();

  // format hh:mm
  if (/^\d{1,2}:\d{2}$/.test(value)) {
    const [h, m] = value.split(':').map(Number);
    return (h * 60 + m);
  }

  // format minutes
  if (/^\d+$/.test(value))
    return (parseInt(value, 10));

  return (0);
}

// Format les minutes pour être sous la forme (HH:MM).
function formatTime(totalMinutes) {
	let heure;
	let minute;
	let formatedString;

	if (totalMinutes < 0)
		totalMinutes = 0;
	heure = Math.floor(totalMinutes / 60);
	minute = totalMinutes % 60;
	formatedString = String(heure).padStart(2, '0') + ':' + String(minute).padStart(2, '0');
	return (formatedString);
}

// Update le temps théorique.
function ajustTime(delta) {
	let docTempsTheorique;
	let minutes;

	docTempsTheorique = document.getElementById("temps-theorique");
	minutes = parseTime(docTempsTheorique.value); 
	minutes += delta;
	if (minutes < 0)
		minutes = 0;
	docTempsTheorique.value = formatTime(minutes);
}

// --------------------------- TEMPS THEORIQUE --------STOP---------------------------------
