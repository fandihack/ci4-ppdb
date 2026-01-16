<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-lg-6">

        <div class="card shadow-lg border-0">

            <!-- Header -->
            <div class="card-header bg-danger text-white py-3">
                <h4 class="mb-0 fw-bold">
                    <i class="bi bi-x-circle"></i> Verifikasi Gagal
                </h4>
                <small class="opacity-75">
                    Data tidak ditemukan pada sistem pusat
                </small>
            </div>

            <div class="card-body p-4">

                <!-- Alert utama -->
                <div class="alert alert-danger d-flex align-items-start gap-2">
                    <i class="bi bi-exclamation-triangle fs-5"></i>
                    <div>
                        NISN <strong><?= esc($nisn) ?></strong> <b>tidak ditemukan</b>
                        pada sistem <b>Kemendikbud</b>.
                    </div>
                </div>

                <!-- Penjelasan -->
                <p class="text-muted mt-3 mb-4">
                    Hal ini bisa terjadi karena salah satu kondisi berikut:
                </p>

                <ul class="text-muted small">
                    <li>NISN salah ketik atau kurang digit</li>
                    <li>Data NISN belum sinkron di sistem pusat</li>
                    <li>Siswa belum memiliki NISN resmi</li>
                </ul>

                <hr>

                <!-- Action -->
                <div class="d-flex flex-column flex-md-row gap-2 justify-content-end">
                    <a href="<?= site_url('tracking') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Cek Ulang NISN
                    </a>

                    <a href="<?= site_url('register') ?>" class="btn btn-danger">
                        <i class="bi bi-pencil-square"></i> Kembali ke Pendaftaran
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
