<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-lg-7">

        <div class="card shadow-lg border-0">

            <!-- Header -->
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0 fw-bold">
                    <i class="bi bi-clipboard-check"></i> Status Pendaftaran
                </h4>
                <small class="opacity-75">
                    Hasil evaluasi posisi Anda saat ini
                </small>
            </div>

            <div class="card-body p-4">

                <!-- IDENTITAS SISWA -->
                <h6 class="fw-bold mb-3 text-muted">
                    <i class="bi bi-person"></i> Data Peserta
                </h6>

                <div class="row mb-2">
                    <div class="col-4 text-muted">Nama</div>
                    <div class="col-8 fw-semibold"><?= esc($student->name) ?></div>
                </div>

                <div class="row mb-2">
                    <div class="col-4 text-muted">NISN</div>
                    <div class="col-8 fw-semibold"><?= esc($student->nisn) ?></div>
                </div>

                <div class="row mb-4">
                    <div class="col-4 text-muted">Nilai Akhir</div>
                    <div class="col-8 fw-semibold">
                        <?= number_format($student->final_score, 2) ?>
                    </div>
                </div>

                <hr>

                <!-- HASIL SELEKSI -->
                <h6 class="fw-bold mb-3 text-muted">
                    <i class="bi bi-bar-chart"></i> Hasil Seleksi
                </h6>

                <div class="row mb-3">
                    <div class="col-4 text-muted">Jurusan Saat Ini</div>
                    <div class="col-8 fw-semibold">
                        <?= $student->accepted_major ?? '<span class="text-muted">Belum ditentukan</span>' ?>
                    </div>
                </div>

                <?php if ($position): ?>
                    <div class="row mb-3">
                        <div class="col-4 text-muted">Peringkat</div>
                        <div class="col-8 fw-semibold">
                            #<?= $position ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- STATUS BADGE -->
                <div class="mb-3">
                    <span class="badge px-3 py-2 fs-6 bg-<?= esc($status['color']) ?>">
                        <?= esc($status['label']) ?>
                    </span>
                </div>

                <!-- STATUS MESSAGE -->
                <div class="alert alert-<?= esc($status['color']) ?> mb-0">
                    <i class="bi bi-info-circle"></i>
                    <?= esc($status['message']) ?>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="card-footer bg-light text-end">
                <a href="/tracking" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Cek NISN Lain
                </a>
            </div>

        </div>

    </div>
</div>

<?= $this->endSection() ?>
