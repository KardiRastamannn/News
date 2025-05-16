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
    #content {
        min-height: 200px;
        resize: vertical;
    }
</style>
<div class="container">
    <div class="content-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-users me-2"></i>Felhasználók kezelése</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openUserForm()">+ Új felhasználó</button>
        </div>
        <div class="mb-3">
            <input type="text" id="newsSearchUsers" class="form-control" placeholder="🔍 Keresés a felhasználók között...">
        </div>
        <!-- Felhasználói tábla -->
        <div class="table-responsive mb-4">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>Email</th><th>Szerepkör</th><th>Akciók</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Nincs egyetlen felhasználó sem.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr data-user-id="<?= htmlspecialchars($user['user_id']) ?>">
                                <td><?= htmlspecialchars($user['user_id']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                        <?= htmlspecialchars($user['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editUser(<?= htmlspecialchars($user['user_id']) ?>)">✏️</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= htmlspecialchars($user['user_id']) ?>)">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal (form) -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="user-form" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">Felhasználó</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="user_id" id="user_id">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Jelszó</label>
          <input type="password" name="password" id="password" class="form-control">
        </div>
        <div class="mb-3">
          <label for="role" class="form-label">Szerepkör</label>
          <select name="role" id="role" class="form-select">
            <option value="user">Felhasználó</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Mentés</button>
      </div>
    </form>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
	const messageBox = document.getElementById('ajax-message');
    const form = document.getElementById('user-form');
    if (!form) return;

    // Form elküldése
	form.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch('/admin/users', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(text => {
        console.log('Szerver válasz:', text);
		bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
        if (text.trim() > 0 ) {
			showToast('Sikeres mentés!', 'success');
			sessionStorage.setItem('toastMessage', 'Sikeresen mentve!');
			sessionStorage.setItem('toastType', 'success'); 
            location.reload();
        }else{
			showToast('Sikertelen mentés!', 'danger');
		}
    })
	.catch(() => showToast('Sikertelen mentés!', 'danger'));
    });

    // Kereső a felhasználókhoz
    const searchInput = document.getElementById("newsSearchUsers");
    const tableBody = document.getElementById("user-table-body");

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

    // Új felhasználónál reseteli a formot
    function openUserForm() {
        document.getElementById('user-form').reset();
        document.getElementById('user_id').value = '';
    }

    // Felhasználó szerkesztése AJAX-al
    function editUser(id) {
        fetch(`/admin/users/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json()) 
        .then(user => {
            document.getElementById('user_id').value = user[0].user_id;
            document.getElementById('email').value = user[0].email;
            document.getElementById('role').value = user[0].role;
            new bootstrap.Modal(document.getElementById('userModal')).show();
        })
        .catch(() => showToast('Hiba a felhasználó betöltésekor', 'danger'));
    }

    // Felhasználó törlése AJAX-al
    function deleteUser(id) {
        if (confirm('Biztosan törlöd ezt a felhasználót?')) {
            fetch('/admin/users', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ delete_user_id: id })
            })
            .then(res => res.text())
            .then(text => {
                console.log('Szerver válasz:', text);
                if (text.trim() > 0) {
                    showToast('Sikeres törlés!', 'success');
                    sessionStorage.setItem('toastMessage', 'Sikeres törlés!');
                    sessionStorage.setItem('toastType', 'success');
                    location.reload();
                } else {
                    showToast('Sikertelen törlés!', 'danger');
                }
            })
            .catch(() => showToast('Sikertelen törlés!', 'danger'));
        }
    }
});
</script>
