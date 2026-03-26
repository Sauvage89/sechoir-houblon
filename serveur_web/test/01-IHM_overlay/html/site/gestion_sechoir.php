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
  <button class="overlay-close" onclick="hideOverlay()">&times;</button>

  <div id="overlay-content">
    <h2 id="overlayTitle"></h2>

    <label>Variété houblon</label>
    <input type="text" id="inputVariete" placeholder="ex: Cascade">

    <label>Température cible (°C)</label>
    <input type="number" id="inputTemp" placeholder="ex: 18">

    <button onclick="sauvegarder()">Sauvegarder</button>
  </div>
</div>


<script>
const etages = { 1: {}, 2: {}, 3: {}, 4: {} };
let etageActif = null;

function showOverlay(id) {
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
</script>