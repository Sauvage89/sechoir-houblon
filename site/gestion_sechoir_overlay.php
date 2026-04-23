<div id="overlay">
  <div id="overlay-box">

    <button class="overlay-close" onclick="hideOverlay()">&times;</button>

    <h2 id="overlayTitle"></h2>
  
    <div id="overlay-content">
      <h2>Configuration d'un lot de houblon</h2>

      <div id="gestion_lot" class="field">
        <p id="info_lot">Cette étage ne contient pas de lot</p>
        <p id="id_lot">---</p>
        <button onclick="saveNewLot()">Créer un lot</button>
        <button onclick="deleteLot(id)">Supprimer ce lot</button>
      </div>

      <div id="config_lot">
        <div class="field">
	        <label for="inputVariete">Variété de houblon</label>
      
          <select id="inputVariete" name="variete" required>
            <option value="" disabled selected>-- Sélectionner une variété --</option>
            <option value="" disabled>Chargement...</option>
          </select>
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
          <button type="button" class="btn-save" onclick="callSauvegarde()">Sauvegarder la configuration</button>
        </div>
      </div>
    </div>

  <div id="overlay-actions">
    <button onclick="hideOverlay()">Quitter</button>
  </div>

  </div>
</div>

<script>
function	showOverlay(idEtage) {
  document.getElementById("overlayTitle").textContent = "Étage " + idEtage;
  document.getElementById("overlay").style.display = "flex";
}
function	hideOverlay() {
	document.getElementById("overlay").style.display = "none";
}

function  showConfigLot() {
  document.getElementById("config_lot").style.display = "flex";
}

function  hideConfigLot() {
  document.getElementById("config_lot").style.display = "none";
}

function  saveNewLot() {
  showConfigLot();
  document.getElementById("id_lot").textContent = "Chargement";
  document.getElementById("info_lot").textContent = "Cette étage contient un lot.";
  document.getElementById("info_lot").style.color = "green";
  fetch("../api/sauvegarde_new_lot.php");
}

function  deleteLot(idLot) {
  hideConfigLot();
  document.getElementById("id_lot").textContent = "---";
  document.getElementById("info_lot").textContent = "Cette étage ne contient pas de lot.";
  document.getElementById("info_lot").style.color = "red";
  fetch("../api/delete_lot.php");
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

function	callSauvegarde() {
	fetch("../api/sauvegarde_config_lot.php");
}

</script>