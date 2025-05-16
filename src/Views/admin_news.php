<style>
    body {
        background-color: #f8f9fa;
    }
    .content-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 2rem auto;
        padding: 2rem;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
    }
</style>
<div class="container">
    <div class="content-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-blog me-2"></i>Hírek kezelése
            </h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newModal" onclick="openNewForm()">
                <i class="fas fa-plus me-2"></i>Új
            </button>
        </div>
        <div class="mb-3">
          <input type="text" id="newsSearchInput" class="form-control" placeholder="🔍 Keresés a hírek között...">
        </div>
        <div class="table-responsive mb-4">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <!-- <th>ID</th> -->
                        <th>Cím</th>
                        <th>Rövid bevezető</th>
                        <th>Szerző</th>
                        <th>Publikálva</th>
                        <th>Akciók</th>
                    </tr>
                </thead>
                <tbody id="news-table-body">
                    <?php if (empty($news)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Jelenleg nincs egyetlen hír sem.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($news as $new): ?>
                        <tr data-new-id="<?= htmlspecialchars($new['news_id']) ?>">
                            <!-- <td><?= htmlspecialchars($new['news_id']) ?></td> -->
                            <td><?= htmlspecialchars($new['title']) ?></td>
                            <td><?= htmlspecialchars($new['intro']) ?></td>
                            <td><?= htmlspecialchars($new['author']) ?></td>
                            <td>
                              <?php if (isset($new['publish_at']) && !empty($new['publish_at'])): ?>
                                  <span class="badge bg-success"><?= htmlspecialchars($new['publish_at']) ?></span>
                              <?php else: ?>
                                  <span class="badge bg-warning">Nincs publikálva</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <div class="d-flex flex-nowrap gap-1">
                                  <button class="btn btn-warning btn-sm" onclick="editNew(<?= htmlspecialchars($new['news_id']) ?>)">
                                      <i class="fas fa-edit"></i>
                                  </button>
                                  <button class="btn btn-danger btn-sm" onclick="deleteNew(<?= htmlspecialchars($new['news_id']) ?>)">
                                      <i class="fas fa-trash"></i>
                                  </button>
                              </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal a poszt űrlaphoz -->
<div class="modal fade" id="newModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form id="new-form" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="newModalLabel">Hír</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="news_id" id="news_id">

        <div class="mb-3">
          <label for="title" class="form-label"><i class="fas fa-heading me-2"></i>Cím</label>
          <input type="text" name="title" id="title" class="form-control" required>
        </div>
         <div class="mb-3">
          <label for="image" class="form-label"><i class="fas fa-image me-2"></i>Kép</label>
          <input type="file" name="image" id="image" class="form-control" accept="image/*">
          <div id="current-image" class="mt-2"></div>
        </div>
        <div class="mb-3">
          <label for="intro" class="form-label"><i class="fas fa-heading me-2"></i>Rövid bevezető</label>
          <input type="text" name="intro" id="intro" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="content" class="form-label"><i class="fas fa-file-alt me-2"></i>Tartalom</label>
          <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
          <label for="publish_at" class="form-label"><i class="fas fa-calendar me-2"></i>Publikálási dátum</label>
          <input type="datetime-local" name="publish_at" id="publish_at" class="form-control">
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">
          <i class="fas fa-save me-2"></i>Mentés
        </button>
      </div>
    </form>
  </div>
</div>
<script>
	document.addEventListener('DOMContentLoaded', () => {
    const newForm = document.getElementById('new-form');
    const newModalEl = document.getElementById('newModal');
    const newModal = new bootstrap.Modal(newModalEl);

    // Új poszt űrlap megnyitása - űrlap törlése
    window.openNewForm = function () {
      newForm.reset();
      document.getElementById('news_id').value = '';
      document.getElementById('current-image').innerHTML = '';
      newModal.show();
    };

    // Szerkesztéshez adat betöltése AJAX-szal
    window.editNew = function(newId) {
      fetch(`/admin/new/${newId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(res => res.json())
      .then(news => {
        document.getElementById('news_id').value = news[0].news_id;
        document.getElementById('title').value = news[0].title;
        document.getElementById('content').value = news[0].content;
        document.getElementById('intro').value = news[0].intro;
        document.getElementById('publish_at').value = news[0].publish_at ? news[0].publish_at.replace(' ', 'T') : '';
        if (news[0].image) {
          document.getElementById('current-image').innerHTML = `<p>Jelenlegi kép:</p><img src="/${news[0].image}" alt="Előnézeti kép" class="img-fluid rounded" style="max-width: 200px;">`;
        } else {
          document.getElementById('current-image').innerHTML = '';
        }
        newModal.show();
      })
      .catch(() => showToast('Hiba az adatok lekérésekor', 'danger'));
    };

    // Hír törlés AJAX-szal
    window.deleteNew = function(newId) {
      if (!confirm('Biztosan törlöd ezt a hírt?')) return;

      fetch('/admin/news', {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({ delete_news_id: newId })
      })
      .then(res => res.text())
      .then(text => {
        if (text.trim() > 0 ) {
          showToast('Hír törölve!', 'success');
          sessionStorage.setItem('toastMessage', 'Hír törölve!');
          sessionStorage.setItem('toastType', 'success'); // vagy window.location.href = ...
          location.reload();
        } else {
          showToast('Hiba történt a törléskor.', 'danger');
        }
      })
      .catch(() => showToast('Hálózati hiba!', 'danger'));
    };

    // Form elküldése AJAX-szal (beleértve a képfeltöltést is)
    newForm.addEventListener('submit', e => {
      e.preventDefault();

      const formData = new FormData(newForm);

      fetch('/admin/news', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(res => res.text())
      .then(text => {
        if (text.trim() > 0 ) {
            showToast('Sikeres mentés!', 'success');
            sessionStorage.setItem('toastMessage', 'Sikeresen mentve!');
            sessionStorage.setItem('toastType', 'success'); 
            location.reload();
        }else{
        showToast('Sikertelen mentés!', 'danger');
      }
      })
      .catch(() => showToast('Hálózati hiba!', 'danger'));
    });

    const searchInput = document.getElementById("newsSearchInput");
    const tableBody = document.getElementById("news-table-body");

    searchInput.addEventListener("keyup", function () {
      const filter = searchInput.value.toLowerCase();
      const rows = tableBody.getElementsByTagName("tr");

      Array.from(rows).forEach(row => {
        const cells = row.getElementsByTagName("td");
        let match = false;

        for (let i = 0; i < cells.length - 1; i++) { // utolsó cella az akciók, azt kihagyjuk
          if (cells[i] && cells[i].textContent.toLowerCase().includes(filter)) {
            match = true;
            break;
          }
        }

        row.style.display = match ? "" : "none";
      });
    });
  });
  </script>
