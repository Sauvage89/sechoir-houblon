<div class="container-fluid px-3 px-sm-4 pt-3" style="max-width:760px; margin:auto;">

  <!-- 1 — Type d'export -->
  <section class="compartiment" id="comp-type">
    <h2 class="comp-label">1 — Type d'export</h2>
    <div class="type-list">
  
      <!-- Type d'export LOT -->
      <button class="type-item" id="btn-type-lot" onclick="selectType('lot')">
        <span class="dot dot-green"></span>
        <span>Par lot — export détaillé d'un lot</span>
      </button>

      <!-- Type d'export PRODUCTION -->
      <button class="type-item" id="btn-type-production" disabled onclick="selectType('production')">
        <span class="dot dot-orange"></span>
        <span>
          Par production — tous les lots d'une production finale
          <span class="wip-tag d-block">Work in progress</span>
        </span>
      </button>

    </div>
  </section>

  <!-- 2 — Filtres -->
  <section class="compartiment" id="comp-filtres" hidden>
    <h2 class="comp-label">2 — Filtres</h2>

    <!-- Type filtre LOT -->
    <div id="filtres-lot" hidden>
      <div class="filter-grid">
        <div class="filter-group">
          <div class="filter-name">Variété</div>
          <select id="lot-variete">
            <option value="" selected>Toutes</option>
          </select>
          <span id="variete-msg" class="form-msg"></span>
        </div>
        <div class="filter-group">
          <div class="filter-name">N° de lot</div>
          <input type="text" id="lot-numero" placeholder="ex. LOT-027">
        </div>
        <div class="filter-group">
          <div class="filter-name">Températures</div>
          <div class="etage-checks">
            <label><input id="chexbox-temperature" type="checkbox" class="etage-check" checked> Export de toutes les températures du lot</label>
          </div>
        </div>
        <div class="filter-group">
          <div class="filter-name">Événements</div>
          <div class="etage-checks">
            <label><input id="chexbox-evenement" type="checkbox" class="etage-check"> Export de tous les événements du lot</label>
          </div>
        </div>
      </div>
    </div>

    <!-- Type filtre PRODUCTION -->
    <div id="filtres-production" hidden>
      <div class="filter-grid">
        <div class="filter-group">
          <div class="filter-name">Variété</div>
          <select id="prod-variete">
            <option value="">Toutes</option>
            <option>Strisselspalt</option>
            <option>Aramis</option>
            <option>Brewers Gold</option>
            <option>Magnum</option>
          </select>
        </div>
        <div class="filter-group">
          <div class="filter-name">N° de production</div>
          <input type="text" id="prod-numero" placeholder="ex. PROD-005">
        </div>
      </div>
    </div>

    <!-- Type filtre COMMUN -->
    <div class="filter-actions">
      <button class="btn" onclick="resetFiltreResults()">Réinitialiser</button>
      <button class="btn btn-search" onclick="loadTable()">Rechercher</button>
    </div>
  </section>

  <!-- 3 — Résultats -->
  <section class="compartiment" id="comp-results" hidden>
    <h2 class="comp-label">3 — Résultats</h2>
    <div class="results-meta">
      <span class="results-count" id="result-count">—</span>
    </div>
    <div id="result-table-wrap">
      <p class="empty-state">Applique les filtres pour voir les résultats.</p>
    </div>
  </section>

</div>

<script src="/../js/exportCSV/GestionSTATE.js" defer></script>
<script src="/../js/exportCSV/GestionUI.js" defer></script>
<script src="/../js/exportCSV/GestionUTILS.js" defer></script>
<script src="/../js/wrapper.js" defer></script>
<script src="/../js/exportCSV/index.js" defer></script>