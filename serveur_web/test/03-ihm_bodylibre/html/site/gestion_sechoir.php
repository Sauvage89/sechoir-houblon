<div class="etage-row">
  <button onclick="descendre(4)">↓</button>
  <button onclick="showOverlay(4)">Étage 4</button>
</div>
<div class="etage-row">
  <button onclick="descendre(3)">↓</button>
  <button onclick="showOverlay(3)">Étage 3</button>
</div>
<div class="etage-row">
  <button onclick="descendre(2)">↓</button>
  <button onclick="showOverlay(2)">Étage 2</button>
</div>
<div class="etage-row">
  <button disabled>↓</button>
  <button onclick="showOverlay(1)">Étage 1</button>
</div>


<div id="overlay">
  <div id="overlay-box">

    <button class="overlay-close" onclick="hideOverlay()">&times;</button>

    <h2 id="overlayTitle">Mon titre</h2>
  
    <div id="overlay-content">
      
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
          <button type="button" onclick="ajusterRemplissage(-10)">−</button>
          <span id="remplissageVal">0</span>
          <span>%</span>
          <button type="button" onclick="ajusterRemplissage(+10)">+</button>
        </div>
      </div>

      <div class="field">
        <label>Test script</label>
          <button type="button" onclick="callScript()">BUTTON</button>
      </div>
  
    </div>

  <div id="overlay-actions">
    <button onclick="hideOverlay()">Annuler</button>
    <button onclick="sauvegarder()">Sauvegarder</button>
  </div>

  </div>
</div>



<script>
const etages = { 1: {}, 2: {}, 3: {}, 4: {} };
let etageActif = null;
let remplissage = 0;

function showOverlay(id) {
  document.getElementById("overlayTitle").textContent = "Étage " + id;
  document.getElementById("overlay").style.display = "flex";
}

function hideOverlay() {	
  document.getElementById("overlay").style.display = "none";
}

function sauvegarder() {
  etages[etageActif] = {
    variete: document.getElementById("inputVariete").value,
    temp: document.getElementById("inputTemp").value,
  };
  hideOverlay();
}

function descendre(id) {
  if (id >= 4) return;
  etages[id + 1] = { ...etages[id] };
  etages[id] = {};
}

function ajusterRemplissage(delta) {
  remplissage = Math.min(100, Math.max(0, remplissage + delta));
  document.getElementById('remplissageVal').textContent = remplissage;
}

function	callScript() {
	fetch("../api/api.php");
}
</script>