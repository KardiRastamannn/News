<style>
    body {
        background-color: #f8f9fa;
    }

    .content-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin: 2rem auto;
        padding: 2rem;
    }

    .new-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .new-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .new-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .new-card .card-body {
        padding: 1.5rem;
    }

    .new-meta {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .new-meta i {
        margin-right: 0.5rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .card-text {
        font-size: 0.95rem;
        color: #444;
    }

    .read-more-btn {
        margin-top: 1rem;
    }
</style>

<div class="container">
    <div class="content-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-newspaper me-2"></i>Friss hírek</h2>
        </div>

        <div class="row">
            <?php if (empty($news)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        Jelenleg nincs elérhető hír. Kérjük, térjen vissza később!
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($news as $new): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="new-card card h-100">
                            <?php if (!empty($new['image'])): ?>
                                <img src="/<?= htmlspecialchars($new['image']) ?>" alt="Bejegyzés képe" class="new-image">
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($new['title']) ?></h5>
                                <div class="new-meta">
                                    <span><i class="fas fa-user"></i><?= htmlspecialchars($new['author']) ?></span>
                                    <span class="ms-3"><i class="fas fa-calendar"></i><?= htmlspecialchars($new['publish_at']) ?></span>
                                </div>
                                <p class="card-text">
                                    <?= nl2br(htmlspecialchars($new['intro'] ?? 'Nincs bevezető szöveg.')) ?>
                                </p>
                                <a href="/new/<?= htmlspecialchars($new['news_id']) ?>" class="btn btn-outline-primary mt-auto read-more-btn">
                                    <i class="fas fa-book-reader me-2"></i>Olvasd tovább
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>