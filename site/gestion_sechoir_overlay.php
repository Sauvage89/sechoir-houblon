<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<div id="overlay">
  <div id="overlay-box">

    <button class="overlay-close" onclick="hideOverlay()">&times;</button>

    <h2 id="overlayTitle"></h2>
  
    <div id="overlay-content">
      <h2>Configuration d'un lot de houblon</h2>

      <div id="gestion_lot" class="field">
        <p id="info-lot"></p>
        <p id="id-lot"></p>

				<div class="field">
	        <label for="inputVariete">Variété de houblon</label>
      
					<select id="inputVariete" name="variete" required>
	          <option value="" disabled selected>-- Sélectionner une variété --</option>
          </select>
          <span id="variete-msg" class="form-msg"></span>
        </div>

	      <div class="field">
          <label>Remplissage</label>
          <div id="remplissage-control">
            <button type="button" onclick="ajustRemplisage(-10)">−</button>
            <span id="remplissageVal">0</span>
            <span>%</span>
            <button type="button" onclick="ajustRemplisage(+10)">+</button>
          </div>
        </div>

        <div class="field">
          <label>Temps de séchage voulue</label>
          <div id="remplissage-control">
            <input id="temps-theorique" placeholder="hh:mm (01:30 ou 02:00)">
            <button type="button" onclick="ajustTime(-10)">−</button>
            <button type="button" onclick="ajustTime(+10)">+</button>
          </div>
        </div>

        

        <button id="btn-lot-save" onclick="handleLot()">Créer un lot</button>
        <button id="btn-lot-delete" onclick="deleteLot()">Supprimer ce lot</button>
      </div>

    </div>

  <div id="overlay-actions">
    <button onclick="hideOverlay()">Quitter</button>
  </div>

  </div>
</div>

<script>
let ID_ETAGE;
let LOT;

document.addEventListener("DOMContentLoaded", get_variete);

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

	// IMPORTANT : message brut
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

async function set_config_lot() {
  if (!ID_ETAGE) return;

  LOT = await get_lot(ID_ETAGE);
	const doc_info_lot = document.getElementById("info-lot");
	const doc_id_lot = document.getElementById("id-lot");
  const doc_remplissageVal = document.getElementById("remplissageVal");
  const doc_variete = document.getElementById("inputVariete");
  const doc_btn_save = document.getElementById("btn-lot-save");
  const doc_temps_theorique = document.getElementById("temps-theorique");

	if (LOT)
	{
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

	}
	else
	{
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

function	hideOverlay() {
	document.getElementById("overlay").style.display = "none";
	rafraichirStatus();
}

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
	}
	else {
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
  if (!varieteId || varieteId <= 0)
  {
    showVarieteMessage("Veuillez sélectionner une variété");
    return;
  }

  if (Number.isNaN(remplissage))
  {
    console.error("Remplissage invalide");
    return;
  }

  document.getElementById("id-lot").textContent = "Chargement";
	const res = await fetch("../api/query_save_lot_on_etage.php",
	{
		method: "POST",
		headers:
		{
			"Content-Type": "application/x-www-form-urlencoded"
		},
		body: new URLSearchParams(
		{
			etage: ID_ETAGE,
			variete: varieteId,
			remplissage: remplissage,
      temps_theorique: tempsTheorique
		})
	});

	const data = await res.json();
	
	if (data.status == "ok") set_config_lot();
}

function showVarieteMessage(text, isError = true)
{
	const msg = document.getElementById("variete-msg");

	msg.textContent = text;
	msg.style.color = isError ? "red" : "green";
	msg.classList.add("show");

	setTimeout(() => {
		msg.classList.remove("show");
	}, 5000);
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

async function get_lot_nom(etage) {	
	const res = await fetch(`../api/query_get_nom_etage.php?etage=${etage}`);
	const data = await res.json();
	return (data?.nom ?? null);
}

async function get_lot(etage) {
  const res = await fetch(`../api/query_get_lot.php?etage=${etage}`);
	const data = await res.json();

	return (data.lot);
} 

function parseTime(value) {
  value = value.trim();

  // format hh:mm
  if (/^\d{1,2}:\d{2}$/.test(value)) {
    const [h, m] = value.split(':').map(Number);
    return h * 60 + m;
  }

  // format minutes (ex: 90)
  if (/^\d+$/.test(value)) {
    return parseInt(value, 10);
  }

  return 0; // fallback si vide ou invalide
}

function formatTime(totalMinutes) {
  totalMinutes = Math.max(0, totalMinutes); // pas de négatif

  const h = Math.floor(totalMinutes / 60);
  const m = totalMinutes % 60;

  return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
}

function ajustTime(delta) {
  const input = document.getElementById("temps-theorique");
  console.log(input.value);
  let minutes = parseTime(input.value); 
  minutes += delta;
  if (minutes < 0) minutes = 0;

  input.value = formatTime(minutes);
}

</script>