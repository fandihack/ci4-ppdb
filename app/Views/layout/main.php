<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'PPDB Online') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --ppdb-primary: #0d47a1;
            --ppdb-secondary: #1565c0;
            --ppdb-accent: #42a5f5;
            --ppdb-bg: #f4f6fb;
            --ppdb-border: #e0e4ea;
        }

        body {
            background: linear-gradient(180deg, #eef3ff 0%, var(--ppdb-bg) 120px);
            min-height: 100vh;
        }

        /* TOP BAR */
        .topbar {
            background: linear-gradient(90deg, #0b3c8a, #0d47a1);
            color: #e3f2fd;
            font-size: .8rem;
        }

        /* NAVBAR */
        .navbar-ppdb {
            background: linear-gradient(
                135deg,
                var(--ppdb-primary),
                var(--ppdb-secondary)
            );
            box-shadow: 0 8px 24px rgba(13,71,161,.25);
        }

        .navbar-brand {
            letter-spacing: .3px;
        }

        .navbar-brand .badge {
            font-size: .6rem;
            background: rgba(255,255,255,.15);
        }

        .nav-link {
            color: rgba(255,255,255,.85) !important;
            font-weight: 500;
            border-radius: 10px;
            padding: .45rem .9rem;
            transition: all .25s ease;
        }

        .nav-link.active,
        .nav-link:hover {
            background: rgba(255,255,255,.18);
            color: #fff !important;
            transform: translateY(-1px);
        }

        /* PPDB STATUS */
        .ppdb-indicator {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .75rem;
            font-weight: 700;
            padding: .35rem .75rem;
            border-radius: 20px;
            white-space: nowrap;
        }

        .ppdb-indicator::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 1.6s infinite;
        }

        .ppdb-open {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .ppdb-open::before {
            background: #2e7d32;
        }

        .ppdb-close {
            background: #fdecea;
            color: #c62828;
        }

        .ppdb-close::before {
            background: #c62828;
            animation: none;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(46,125,50,.6); }
            70% { box-shadow: 0 0 0 6px rgba(46,125,50,0); }
            100% { box-shadow: 0 0 0 0 rgba(46,125,50,0); }
        }

        /* PAGE HEADER */
        .page-header {
            background:
                radial-gradient(circle at top right, rgba(66,165,245,.25), transparent 45%),
                #ffffff;
            border-bottom: 1px solid var(--ppdb-border);
        }

        /* FOOTER */
        footer {
            background: #ffffff;
            border-top: 1px solid var(--ppdb-border);
        }
    </style>
</head>
<body>

<!-- TOP BAR -->
<div class="topbar py-1">
    <div class="container d-flex justify-content-between align-items-center">
        <span><i class="bi bi-shield-check me-1"></i> Sistem Resmi PPDB Sekolah Unggulan</span>
        <span>Tahun Ajaran <?= date('Y') ?>/<?= date('Y') + 1 ?></span>
    </div>
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-ppdb sticky-top">
    <div class="container">

        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/">
            <i class="bi bi-mortarboard-fill fs-4"></i>
            <span>PPDB Online</span>
            <span class="badge">OFFICIAL</span>
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            <ul class="navbar-nav me-auto gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link <?= uri_string()==''?'active':'' ?>" href="/">
                        <i class="bi bi-house-door me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains(uri_string(),'register')?'active':'' ?>" href="/register">
                        <i class="bi bi-pencil-square me-1"></i> Pendaftaran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains(uri_string(),'tracking')?'active':'' ?>" href="/tracking">
                        <i class="bi bi-search me-1"></i> Cek Status
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains(uri_string(),'ranking')?'active':'' ?>" href="/ranking">
                        <i class="bi bi-bar-chart-line me-1"></i> Peringkat
                    </a>
                </li>
            </ul>

            <div class="d-flex gap-2 align-items-center mt-3 mt-lg-0">
                <?php if ($isPpdbActive ?? false): ?>
                    <span class="ppdb-indicator ppdb-open">PPDB AKTIF</span>
                <?php else: ?>
                    <span class="ppdb-indicator ppdb-close">PPDB DITUTUP</span>
                <?php endif; ?>

                <a href="/admin" class="btn btn-light btn-sm fw-semibold shadow-sm">
                    <i class="bi bi-lock me-1"></i> Admin
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- PAGE HEADER -->
<?php if (!empty($pageTitle)): ?>
<section class="page-header py-4 mb-4">
    <div class="container">
        <h1 class="fw-bold mb-1"><?= esc($pageTitle) ?></h1>
        <?php if (!empty($pageSubtitle)): ?>
            <p class="text-muted mb-0"><?= esc($pageSubtitle) ?></p>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- CONTENT -->
<main class="container mb-5">
    <?= $this->renderSection('content') ?>
</main>

<!-- FOOTER -->
<footer class="py-4">
    <div class="container text-center">
        <div class="fw-semibold">Sistem PPDB Online © <?= date('Y') ?></div>
        <small class="text-muted d-block mt-1">
            CodeIgniter 4 · Selection Engine v2.0
        </small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/ppdb.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
