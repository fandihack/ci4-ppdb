<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- =======================
 HERO INSIGHT
======================= -->
<div class="card border-0 shadow-sm mb-5">
    <div class="card-body text-center py-5">
        <h2 class="fw-bold mb-2">
            <i class="bi bi-lightning-charge-fill text-warning"></i>
            Insight Peluang Masuk
        </h2>
        <p class="text-muted mb-0">
            Estimasi ambang aman berdasarkan ±10 peringkat terakhir (real-time)
        </p>
    </div>
</div>

<!-- =======================
 THRESHOLD CARDS
======================= -->
<div class="row g-4 mb-5">
<?php foreach (['IPA'=>'primary','IPS'=>'success','Bahasa'=>'secondary'] as $major => $color): 
    $value = $thresholds[$major] ?? 0;
    $percent = min(100, $value);
?>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm text-center">

            <div class="card-body py-4">

                <span class="badge bg-light text-dark mb-2">
                    Jurusan <?= $major ?>
                </span>

                <div class="display-5 fw-bold text-<?= $color ?> countup"
                     data-target="<?= $value ?>" data-decimals="2">
                    <?= number_format($value, 2) ?>
                </div>

                <small class="text-muted d-block mb-3">
                    Nilai Minimum Aman
                </small>

                <div class="progress mb-3" style="height:8px">
                    <div class="progress-bar bg-<?= $color ?>"
                         style="width:<?= $percent ?>%">
                    </div>
                </div>

                <?php if ($value >= 85): ?>
                    <span class="badge bg-danger mb-3">Zona Kritis</span>
                <?php elseif ($value >= 75): ?>
                    <span class="badge bg-warning text-dark mb-3">Persaingan Ketat</span>
                <?php else: ?>
                    <span class="badge bg-success mb-3">Masih Aman</span>
                <?php endif; ?>

                <a href="/register?major=<?= $major ?>"
                   class="btn btn-outline-<?= $color ?> w-100 fw-semibold mt-2">
                    Daftar Jurusan <?= $major ?>
                </a>

            </div>
        </div>
    </div>
<?php endforeach ?>
</div>

<!-- =======================
 INFO & STAT
======================= -->
<div class="row g-4 mb-5">

    <!-- INFO -->
    <div class="col-md-8">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-info-circle"></i> Informasi PPDB
                </h5>
            </div>

            <div class="card-body">

                <h6 class="fw-bold mb-2">Alur Pendaftaran</h6>
                <ol class="text-muted mb-4">
                    <li>Isi formulir pendaftaran online</li>
                    <li>Validasi & verifikasi NISN otomatis</li>
                    <li>Perhitungan nilai & peringkat real-time</li>
                    <li>Pantau hasil melalui menu Ranking</li>
                </ol>

                <h6 class="fw-bold mb-2">Sistem Seleksi</h6>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-primary">Real-Time Ranking</span>
                    <span class="badge bg-success">Domino Effect</span>
                    <span class="badge bg-warning text-dark">Tie-Breaker Nilai</span>
                </div>

            </div>
        </div>
    </div>

    <!-- STAT -->
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm text-center">
            <div class="card-body py-4">

                <h5 class="fw-semibold mb-3">
                    <i class="bi bi-people-fill"></i> Total Pendaftar
                </h5>

                <div class="display-4 fw-bold text-primary mb-4 countup"
                     data-target="<?= $total_registered ?>">
                    <?= $total_registered ?>
                </div>

                <hr>

                <?php foreach (['IPA'=>'primary','IPS'=>'success','Bahasa'=>'secondary'] as $major => $color): ?>
                    <div class="mb-3 text-start">
                        <div class="d-flex justify-content-between small fw-semibold">
                            <span><?= $major ?></span>
                            <span class="text-muted">Kuota 20</span>
                        </div>
                        <div class="progress mt-1" style="height:6px">
                            <div class="progress-bar bg-<?= $color ?>" style="width:100%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

</div>

<!-- =======================
 COUNT UP SCRIPT
======================= -->
<script>
document.querySelectorAll('.countup').forEach(el => {
    const target   = parseFloat(el.dataset.target || 0);
    const decimals = parseInt(el.dataset.decimals || 0);
    const duration = 800;
    const start    = performance.now();

    function animate(time) {
        const progress = Math.min((time - start) / duration, 1);
        const value = target * progress;
        el.innerText = value.toFixed(decimals);
        if (progress < 1) requestAnimationFrame(animate);
    }

    requestAnimationFrame(animate);
});
</script>

<?= $this->endSection() ?>
