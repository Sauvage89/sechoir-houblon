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


<script src="/../js/gestionSechoirOverlay/index.js" defer></script>