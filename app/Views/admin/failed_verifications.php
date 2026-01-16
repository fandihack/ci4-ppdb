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
                        Total: <?= count($failed_data) ?>
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
                                        <td><?= esc($row->name ?? '-') ?></td>
                                        <td>
                                            <span class="badge bg-danger">
                                                <?= esc($row->reason) ?>
                                            </span>
                                        </td>
                                        <td class="text-center text-muted">
                                            <?= date('d M Y H:i', strtotime($row->created_at)) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($row->attempt_data)): ?>
                                                <button
                                                    class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal<?= $row->id ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            <?php else: ?>
                                                -
                                            <?php endif ?>
                                        </td>
                                    </tr>

                                    <!-- MODAL DETAIL -->
                                    <?php if (!empty($row->attempt_data)): ?>
                                    <div class="modal fade" id="detailModal<?= $row->id ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        Detail Percobaan Pendaftaran
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <pre class="bg-light p-3 rounded small">
<?= esc(json_encode(json_decode($row->attempt_data), JSON_PRETTY_PRINT)) ?>
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
