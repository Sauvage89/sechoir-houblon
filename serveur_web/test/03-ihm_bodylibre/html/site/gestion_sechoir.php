<?php include "overlay_etage_houblon.php"; ?>

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

<!--
// Permet d'afficher l'overlay de l'étage <id>.
function  showOverlay(id);

// Permet d'enlever l'overlay de l'étage <id>.
function  hideOverlay(id);

// Permet de descendre les paramètre d'un lot d'houblon d'un étage a celui de dessous.
function  descendre(id);
-->