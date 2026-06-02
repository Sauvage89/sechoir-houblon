<section id="menu">
  <div id="menu-box">

    <button class="menu-close" onclick="cacherMenu()">X</button> <!-- Bouton quittant le menu!-->
    <h2 id="menuTitre"></h2> <!-- Le titre ce charge automatiquement en ouvrant l'menu !--> 
  
    <div id="menu-content">
      <h2>Configuration d'un lot de houblon</h2>

      <div id="gestion_lot" class="field">
        <p id="info-lot"></p> <!-- Sur cette balise ont donne les infos de si un lot existe ou non sur l'étage x !-->
        <p id="id-lot"></p> <!-- Sur cette balise ont donne l'id du lot qui existe sur l'étage !-->

        <!-- Cette div permet de selectionner une variete d'houblon, elle charge la variete du lot sur l'étage x !-->
				<div class="field">
	        <label>Variété de houblon</label>
					<select id="inputVariete">
	          <option value="" disabled selected>-- Sélectionner une variété --</option>
          </select>
          <span id="variete-msg" class="form-msg"></span>
        </div>

        <!-- Cette div permet de selectionner un taux de remplissage, elle charge le remplissage du lot sur l'étage x !-->
	      <div class="field">
          <label>Remplissage</label>
          <div id="remplissage-control">
            <button type="button" onclick="ajustRemplisage(-10)">−</button>
            <span id="remplissageVal">0</span>
            <span>%</span>
            <button type="button" onclick="ajustRemplisage(+10)">+</button>
          </div>
        </div>

        <div class="field"> <!-- Cette div permet de taper le temps de séchage voulue, elle charge le temps de séchage du lot sur l'étage x !-->
          <label>Temps de séchage voulue</label>
          <div id="remplissage-control">
            <input id="temps-theorique" placeholder="hh:mm (01:30)">
            <button type="button" onclick="ajustTime(-10)">−</button>
            <button type="button" onclick="ajustTime(+10)">+</button>
          </div>
        </div>

        <button id="btn-lot-save" onclick="handleLot()">Créer un lot</button>  <!-- Ce bouton sauvegarde les paramètre renseigner du lot !-->
        <button id="btn-lot-delete" onclick="deleteLot()">Supprimer ce lot</button> <!-- Ce bouton supprime le lot d'houblon !-->

      </div>
    </div>

  <div id="menu-actions">
    <button onclick="cacherMenu()">Quitter</button> <!-- Bouton quittant l'menu !-->
  </div>

  </div>
</section>

<script src="/../js/gestionSechoirMenu/GestionSTATE.js" defer></script>
<script src="/../js/gestionSechoirMenu/GestionUTILS.js" defer></script>
<script src="/../js/gestionSechoirMenu/GestionAPI.js" defer></script>
<script src="/../js/gestionSechoirMenu/GestionUI.js" defer></script>
<script src="/../js/gestionSechoirMenu/index.js" defer></script>