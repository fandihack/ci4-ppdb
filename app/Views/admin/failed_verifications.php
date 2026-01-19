<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row justify-content-center mt-5">
        <div class="col-12">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-x-octagon-fill"></i>
                        Riwayat Gagal Verifikasi Pendaftar
                    </h5>
                    <span class="badge bg-light text-danger">
                        Total: <?= is_array($failed_data) ? count($failed_data) : 0 ?>
                    </span>
                </div>

                <div class="card-body">

                    <?php if (empty($failed_data)): ?>
                        <div class="alert alert-success text-center">
                            <i class="bi bi-check-circle-fill"></i>
                            Tidak ada riwayat pendaftar gagal.
                        </div>
                    <?php else: ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Alasan Gagal</th>
                                    <th>Waktu</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($failed_data as $i => $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $i + 1 ?></td>
                                        <td class="text-center fw-bold"><?= esc($row->nisn) ?></td>
                                        <td><?= esc($row->name ?? 'Pendaftar Tidak Dikenal') ?></td>
                                        <td>
                                            <span class="badge bg-danger">
                                                <?= esc($row->reason) ?>
                                            </span>
                                        </td>
                                        <td class="text-center text-muted">
                                            <?= empty($row->created_at) ? '-' : date('d M Y, H:i', strtotime($row->created_at)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php 
                                                // Logika: Cek apakah attempt_data berisi JSON yang valid dan tidak kosong
                                                $hasAttemptData = !empty($row->attempt_data) && json_decode($row->attempt_data) !== null;
                                            ?>
                                            <?php if ($hasAttemptData): ?>
                                                <button
                                                    class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal<?= $row->id ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted small">Tanpa Data</span>
                                            <?php endif ?>
                                        </td>
                                    </tr>

                                    <?php if ($hasAttemptData): ?>
                                    <div class="modal fade" id="detailModal<?= $row->id ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        Detail Percobaan: <?= esc($row->nisn) ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="small text-muted mb-2">Data mentah yang dikirim pendaftar:</p>
                                                    <pre class="bg-light p-3 rounded small border">
<?php 
    // Logika Prettify JSON agar mudah dibaca di Dashboard Admin
    $decodedData = json_decode($row->attempt_data, true);
    echo esc(json_encode($decodedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
?>
                                                    </pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif ?>

                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <?php endif ?>

                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>