<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hírek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
        .blog-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .blog-card:hover {
            transform: translateY(-3px);
        }
        .blog-card .card-body {
            padding: 1.5rem;
        }
        .blog-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .blog-meta i {
            margin-right: 0.5rem;
        }
        footer {
            background-color: #343a40;
            color: #ccc;
            padding: 1rem 0;
            text-align: center;
            margin-top: 3rem;
        }
        html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
}

main {
    flex: 1;
}
    </style>
	    <!-- Oldalspecifikus stílus, ha szükséges -->
		<?= $extraCss ?? '' ?>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container">
            <a class="navbar-brand" href="/">NewPortal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
					<?php if (isset($user)): ?>
                    <div class="d-flex align-items-center">
                        <span class="me-3">Üdvözöllek, <?php echo htmlspecialchars($user['email']); ?>!</span>
                        <a href="/login" class="btn btn-outline-success m-1">
                            <i class="fas fa-user me-2"></i>Admin  
                        </a>
                        <a href="/logout" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Kijelentkezés
                        </a>
                    </div>
                <?php else: ?>
                    <a href="/login" class="btn btn-outline-success">
                        <i class="fas fa-user me-2"></i>Admin
                    </a>
                <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999">
        <div id="ajax-toast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
            <div class="toast-body" id="ajax-toast-body">Sikeres mentés!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    </div>
    <main class="container flex-grow 1">
        <div id="ajax-content">
            <?= $content ?? '' ?>
        </div>
    </main>
	

    <footer>
        <div class="container">
            <p class="mb-0">© <?= date('Y') ?> NewPortal. Minden jog fenntartva.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AJAX navigáció -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('ajax-content');

            document.querySelectorAll('a[href^="/"]').forEach(link => {
                link.addEventListener('click', function (e) {
                    if (!link.classList.contains('no-ajax')) {
                        e.preventDefault();
                        fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(response => response.text())
                            .then(html => {
                                container.innerHTML = html;
                                history.pushState(null, '', link.href);
                            });
                    }
                });
            });

            window.addEventListener('popstate', () => {
                fetch(location.pathname, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => response.text())
                    .then(html => container.innerHTML = html);
            });
        });

        //Felhasználónak visszajelzés üzenet formájában
        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('ajax-toast');
            const toastBody = document.getElementById('ajax-toast-body');

            // Színt állítjuk a típus alapján
            toastEl.classList.remove('bg-success', 'bg-danger', 'bg-info');
            toastEl.classList.add('bg-' + type);

            toastBody.textContent = message;

            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
        // Login link megakadályozása, hogy AJAX-al fusson le helyette.
        document.addEventListener('click', function (e) {
            if (e.target.matches('a[href="/logout"]')) {
                e.preventDefault();
                fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(() => {
                    // Teljes oldal újratöltés, hogy a layout is frissüljön
                    window.location.href = '/';
                });
            }
        });
        //Toast kezelése
        document.addEventListener('DOMContentLoaded', function () {
            const message = sessionStorage.getItem('toastMessage');
            const type = sessionStorage.getItem('toastType');

            if (message) {
                showToast(message, type || 'success');
                sessionStorage.removeItem('toastMessage');
                sessionStorage.removeItem('toastType');
            }
        });
    </script>
</body>
</html>
