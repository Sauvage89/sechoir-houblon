<!-- SESSION COURANTE -->
<div style="display:flex;flex-direction:column;gap:.5rem;">
  <div class="session-bar">
    <div id="lastSesSech">
      <span class="session-dot"></span>
      <span>Dernière session : <span class="session-val">chargement…</span></span>
    </div>
    <button id="startSechage" class="btn btn-primary">+ Nouvelle session</button>
  </div>
  <p id="resul_startSechaget" class="status-msg" style="align-self:flex-end;"></p>
</div>

<!-- INFO -->
<p class="info">
  Cette page permet une gestion rapide de la session de séchage en cours.<br>
  Pour une gestion complète (suivi, modification, retrait des houblons),
  utilisez l'onglet <strong>Gestion de session</strong>.
</p>

<!-- AJOUTER UN HOUBLON -->
<div class="card">
  <div class="card-header">
    <span class="card-title">Ajouter un houblon</span>
  </div>
  <div class="card-body">
    <form id="addHoublonForm">
      <div class="form-row">
        <div class="field">
          <label for="variete">Variété</label>
          <select id="variete" required>
            <option value="">— Sélectionner —</option>
          </select>
        </div>
        <div class="field">
          <label for="etage">Étage</label>
          <select id="etage">
            <option value="">En attente</option>
            <option value="1">Étage 1 — Souffleur</option>
            <option value="2">Étage 2</option>
            <option value="3">Étage 3</option>
            <option value="4">Étage 4</option>
          </select>
        </div>
        <div class="field">
          <label>&nbsp;</label>
          <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
      </div>
      <p id="result_addHoublon" class="status-msg" style="margin-top:.75rem;"></p>
    </form>
  </div>
</div>


<script>
const	resultStart   = document.getElementById("resul_startSechaget");
const	lastSechEl    = document.getElementById("lastSesSech");
const	resultAdd     = document.getElementById("result_addHoublon");
const	selectVariete = document.getElementById("variete");
let	lastSessionId = null;

function showMsg(el, text, type) {
  el.textContent = text;
  el.className = "status-msg visible " + type;
}
function hideMsg(el) {
  el.className = "status-msg";
}

const lastSechText = document.querySelector("#lastSesSech > span:last-child");

function updateSessionDisplay(session) {
  if (session) {
    const date = new Date(session.ses_sech_date_debut).toLocaleString("fr-FR", {
      day: "2-digit", month: "2-digit", year: "numeric",
      hour: "2-digit", minute: "2-digit"
    });
    lastSechText.innerHTML = `Dernière session <span class="session-val">#${String(session.id_ses_sech).padStart(3,"0")}</span> — démarrée le ${date}`;
  } else {
    lastSechText.textContent = "Aucune session active";
  }
}

async function	loadLastSession() {
  lastSechText.classList.add("dots")
  lastSechText.textContent = "Chargement";
  await new Promise(r => setTimeout(r, 2500));
  try {
    const res  = await fetch("../api/get_last_sechage.php");
    const data = await res.json();
    if (data.status === "ok" && data.lastSession) {
      lastSessionId = data.lastSession.id_ses_sech;
      updateSessionDisplay(data.lastSession);
    } else {
      lastSechText.textContent = "Aucune session trouvée";
    }
  } catch {
    lastSechText.textContent = "Erreur serveur";
  }
  lastSechText.classList.remove("dots");
}

async function loadVarietes() {
  try {
    const res  = await fetch("../api/get_varietes.php");
    const data = await res.json();
    data.varietes.forEach(v => {
      const opt = document.createElement("option");
      opt.value = v.id_houb_var;
      opt.textContent = v.houb_var_type;
      selectVariete.appendChild(opt);
    });
  } catch { /* silently fail */ }
}

document.getElementById("startSechage").addEventListener("click", function () {
  showMsg(resultStart, "Démarrage", "loading");
  fetch("../api/start_sechage.php", { method: "POST" })
    .then(r => r.json())
    .then(data => {
      if (data.status === "ok") {
        showMsg(resultStart, "✓ Session démarrée", "ok");
	setTimeout(() => { loadLastSession(); }, 300);
        setTimeout(() => { hideMsg(resultStart); }, 2500);
      } else {
        showMsg(resultStart, "✗ " + data.message, "error");
      }
    })
    .catch(() => showMsg(resultStart, "✗ Erreur serveur", "error"));
});

document.getElementById("addHoublonForm").addEventListener("submit", async function (e) {
  e.preventDefault();
  if (!selectVariete.value) { showMsg(resultAdd, "✗ Sélectionnez une variété", "error"); return; }
  if (!lastSessionId)       { showMsg(resultAdd, "✗ Aucune session active", "error"); return; }

  showMsg(resultAdd, "Ajout en cours", "loading");
  try {
    const res  = await fetch("../api/add_houblon_sechage.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        session: lastSessionId,
        variete: selectVariete.value,
        etage:   document.getElementById("etage").value
      })
    });
    const data = await res.json();
    if (data.status === "ok") {
      showMsg(resultAdd, "✓ Houblon ajouté", "ok");
      setTimeout(() => hideMsg(resultAdd), 3000);
    } else {
      showMsg(resultAdd, "✗ " + data.message, "error");
    }
  } catch {
    showMsg(resultAdd, "✗ Erreur serveur", "error");
  }
});

window.addEventListener("DOMContentLoaded", () => {
  loadVarietes();
  loadLastSession();
});
</script>