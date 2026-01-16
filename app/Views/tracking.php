<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-lg-6">

        <!-- =======================
            TRACKING CARD
        ======================== -->
        <div class="card shadow-lg border-0">

            <!-- Header -->
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0 fw-bold">
                    <i class="bi bi-search"></i> Cek Status Pendaftaran
                </h4>
                <small class="opacity-75">
                    Pantau posisi Anda secara real-time
                </small>
            </div>

            <div class="card-body p-4">

                <!-- Info Alert -->
                <div class="alert alert-info d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle fs-5"></i>
                    <div>
                        <strong>Tanpa Login</strong><br>
                        Cukup masukkan <b>NISN</b> untuk melihat status pendaftaran Anda.
                    </div>
                </div>

                <!-- Form -->
                <form action="/tracking/check" method="post" class="mt-4">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            NISN (Nomor Induk Siswa Nasional)
                        </label>

                        <div class="input-group input-group-lg">
                            <span class="input-group-text">
                                <i class="bi bi-person-badge"></i>
                            </span>
                            <input
                                type="text"
                                name="nisn"
                                class="form-control"
                                placeholder="Contoh: 1234567890"
                                maxlength="10"
                                required
                            >
                        </div>

                        <div class="form-text">
                            Pastikan NISN sesuai dengan yang didaftarkan (10 digit).
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                            <i class="bi bi-graph-up"></i> Cek Status Sekarang
                        </button>
                    </div>
                </form>

                <!-- Info Result -->
                <div class="mt-5">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-eye"></i> Informasi yang Akan Ditampilkan
                    </h6>

                    <div class="row g-3 text-muted small">
                        <div class="col-md-6">
                            <i class="bi bi-award text-success"></i>
                            Peringkat Anda di jurusan
                        </div>
                        <div class="col-md-6">
                            <i class="bi bi-shield-check text-primary"></i>
                            Status posisi (Aman / Rawan)
                        </div>
                        <div class="col-md-6">
                            <i class="bi bi-diagram-3 text-info"></i>
                            Jurusan penerima saat ini
                        </div>
                        <div class="col-md-6">
                            <i class="bi bi-people text-warning"></i>
                            Status kuota penerimaan
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
