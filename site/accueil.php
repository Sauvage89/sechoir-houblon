<!-- Bloc d'introduction -->
<div class="intro-box">
  <p>Ce site web vous permet de contrôler et de suivre le séchoir à houblon depuis votre téléphone, sans avoir à rester sur place. Voici les différentes fonctionnalités disponibles :</p>
  <div class="nav-cards">
    <div class="nav-card">
      <i class="ti ti-settings"></i>
      <strong>Paramétrage</strong>
      <span>Configurer les variétés, les températures et la durée de séchage de chaque lot.</span>
    </div>
    <div class="nav-card">
      <i class="ti ti-temperature"></i>
      <strong>Contrôle</strong>
      <span>Visualiser les températures des 6 capteurs et piloter le chauffage en temps réel (ce fait de mannière automatique).</span>
    </div>
    <div class="nav-card">
      <i class="ti ti-chart-line"></i>
      <strong>Historique</strong>
      <span>Consulter les données des cycles passés et exporter un fichier CSV sur clé USB.</span>
    </div>
    <div class="nav-card">
      <i class="ti ti-bell"></i>
      <strong>Alertes</strong>
      <span>Être informé en cas de dépassement de température ou de fin de cycle.</span>
    </div>
  </div>
</div>

<!-- Section gestion des variétés -->
<section class="mb-3 mt-5">
  <h2 class="section-title">Gestion des variétés de houblon</h2>
  <div class="floor-card">
    <div class="row g-2">

      <!-- Colonne gauche : supprimer -->
      <div class="col-12 col-md-6 form-group">
        <p class="col-label">Supprimer une variété</p>
        <label>Variété existante</label>
        <select id="inputVariete" name="variete" onclick="removeSpanSupprimerVariete()" required>
          <option value="" disabled selected>
            -- Sélectionner une variété --
          </option>
        </select>
        <span id="supprimer-variete-msg"></span>
        <div class="btn-row">
          <button type="button" onclick="supprimerVariete()">
            Supprimer cette variété
          </button>
        </div>
      </div>

      <!-- Colonne droite : ajouter -->
      <div class="col-12 col-md-6 form-group">
        <p class="col-label">Ajouter une variété</p>
        <label>Nom de la nouvelle variété</label>
        <input id="inputNouvelleVariete" type="text" onclick="removeSpanNouvelleVariete()" placeholder="ex : Cascade" />
        <span id="nouvelle-variete-msg"></span>
        <div class="btn-row">
          <button type="button" onclick="ajouterVariete()">
            <i class="ti ti-plus"></i>
            Ajouter cette variété
          </button>
        </div>
      </div>

    </div>
  </div>
</section>

<script src="/../js/accueil/GestionAPI.js" defer></script>
<script src="/../js/accueil/GestionUI.js" defer></script>
<script src="/../js/accueil/index.js" defer></script>