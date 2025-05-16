<style>
    body {
        background-color: #f8f9fa;
    }

    .content-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin: 3rem auto;
        padding: 2rem;
        max-width: 900px;
    }

    .new-meta {
        color: #6c757d;
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .new-meta i {
        margin-right: 0.5rem;
    }

    .new-image {
        margin-bottom: 1.5rem;
    }

    .new-image img {
        width: 100%;
        height: 350px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .new-content {
        font-size: 1.05rem;
        line-height: 1.7;
        color: #333;
        overflow-wrap: break-word;
        word-wrap: break-word;
        hyphens: auto;
    }

    .back-button {
        text-decoration: none;
        font-size: 0.9rem;
    }
</style>

<div class="container">
    <div class="content-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h1 class="mb-3"><?= htmlspecialchars($new[0]['title']) ?></h1>
            <a href="/" class="btn btn-outline-secondary back-button mt-1">
                <i class="fas fa-arrow-left me-2"></i>Vissza
            </a>
        </div>

        <div class="new-meta mb-3">
            <span><i class="fas fa-user"></i><?= htmlspecialchars($new[0]['author']) ?></span>
            <span class="ms-3"><i class="fas fa-calendar"></i><?= htmlspecialchars($new[0]['publish_at']) ?></span>
        </div>

        <?php if (!empty($new[0]['image'])): ?>
            <div class="new-image">
                <img src="/<?= htmlspecialchars($new[0]['image']) ?>" alt="Bejegyzés képe">
            </div>
        <?php endif; ?>

        <div class="new-content">
            <?= nl2br(htmlspecialchars($new[0]['content'])) ?>
        </div>
    </div>
</div>
