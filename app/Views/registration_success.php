<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-lg-6 col-md-8">

        <div class="card border-0 shadow-sm text-center">

            <!-- HEADER -->
            <div class="card-header bg-success text-white py-4">
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-check-circle-fill"></i>
                    Pendaftaran Berhasil
                </h3>
                <p class="mb-0 small">
                    Data Anda telah tercatat di sistem PPDB
                </p>
            </div>

            <!-- BODY -->
            <div class="card-body py-4">

                <div class="alert alert-success small">
                    <i class="bi bi-info-circle"></i>
                    Simpan <strong>NISN</strong> Anda dengan baik untuk mengecek status pendaftaran.
                </div>

                <ul class="list-group list-group-flush text-start mb-4">
                    <li class="list-group-item">
                        <i class="bi bi-check2-circle text-success"></i>
                        Verifikasi NISN: <strong>Sukses</strong>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-calculator text-primary"></i>
                        Nilai akhir: <strong>Telah dihitung otomatis</strong>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-bar-chart-line text-warning"></i>
                        Peringkat: <strong>Ditentukan secara real-time</strong>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-arrow-repeat text-info"></i>
                        Status dapat berubah sesuai pendaftar baru
                    </li>
                </ul>

                <div class="d-grid gap-2">
                    <a href="/tracking" class="btn btn-primary btn-lg">
                        <i class="bi bi-search"></i> Cek Status Pendaftaran
                    </a>
                    <a href="/" class="btn btn-outline-secondary">
                        <i class="bi bi-house"></i> Kembali ke Beranda
                    </a>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="card-footer bg-light small text-muted">
                Sistem PPDB Online • Ranking & Seleksi Otomatis
            </div>

        </div>

    </div>
</div>

<?= $this->endSection() ?>
