<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-12">

        <!-- HEADER -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold mb-1">
                        <i class="bi bi-trophy text-primary"></i> Ranking Siswa
                    </h3>
                    <p class="text-muted mb-0">
                        Peringkat berdasarkan nilai akhir dan kuota jurusan
                    </p>
                </div>

                <!-- FILTER JURUSAN -->
                <div class="btn-group">
                    <?php foreach (['all' => 'Semua', 'IPA' => 'IPA', 'IPS' => 'IPS', 'Bahasa' => 'Bahasa'] as $key => $label): ?>
                        <a href="/ranking<?= $key !== 'all' ? '/' . $key : '' ?>"
                           class="btn btn-sm btn-outline-primary <?= $selected_major === $key ? 'active' : '' ?>">
                            <?= $label ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- =============================
             MODE: SEMUA JURUSAN
        ============================== -->
        <?php if ($selected_major === 'all'): ?>

            <?php foreach (['IPA', 'IPS', 'Bahasa'] as $major): ?>
                <div class="card border-0 shadow-sm mb-5">

                    <div class="card-header bg-light fw-semibold">
                        <i class="bi bi-mortarboard"></i> Jurusan <?= $major ?>
                        <span class="text-muted small">
                            (Kuota: <?= $quota[$major] ?> siswa)
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">Rank</th>
                                    <th>Nama</th>
                                    <th>NISN</th>
                                    <th>Asal Sekolah</th>
                                    <th class="text-end">Nilai Akhir</th>
                                    <th width="140">Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (!empty($rankings[$major])): ?>
                                    <?php foreach ($rankings[$major] as $index => $student): ?>
                                        <?php
                                            $inQuota = $index < $quota[$major];
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="badge <?= $inQuota ? 'bg-success' : 'bg-secondary' ?>">
                                                    <?= $index + 1 ?>
                                                </span>
                                            </td>
                                            <td><?= esc($student->name) ?></td>
                                            <td><?= esc($student->nisn) ?></td>
                                            <td><?= esc($student->school_origin) ?></td>
                                            <td class="text-end fw-semibold">
                                                <?= number_format($student->final_score, 2) ?>
                                            </td>
                                            <td>
                                                <?php if ($inQuota): ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Diterima
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock-history"></i> Waiting List
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Belum ada data pendaftar jurusan <?= $major ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>

        <!-- =============================
             MODE: PER JURUSAN
        ============================== -->
        <?php else: ?>

            <div class="alert alert-info shadow-sm">
                <i class="bi bi-info-circle"></i>
                Menampilkan ranking jurusan <strong><?= $selected_major ?></strong>.
                Kuota diterima: <strong><?= $quota[$selected_major] ?> siswa</strong>.
            </div>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60">Rank</th>
                                <th>Nama</th>
                                <th>NISN</th>
                                <th>Asal Sekolah</th>
                                <th class="text-end">Nilai Akhir</th>
                                <th>Pilihan 1</th>
                                <th>Jurusan Diterima</th>
                                <th width="140">Status</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (!empty($rankings)): ?>
                                <?php foreach ($rankings as $index => $student): ?>
                                    <?php
                                        $inQuota = $index < $quota[$selected_major];
                                        if ($student->accepted_major === $selected_major) {
                                            $statusClass = $inQuota ? 'bg-success' : 'bg-warning text-dark';
                                            $statusText  = $inQuota ? 'Diterima' : 'Waiting List';
                                        } else {
                                            $statusClass = 'bg-secondary';
                                            $statusText  = 'Jurusan lain';
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="badge <?= $statusClass ?>">
                                                <?= $index + 1 ?>
                                            </span>
                                        </td>
                                        <td><?= esc($student->name) ?></td>
                                        <td><?= esc($student->nisn) ?></td>
                                        <td><?= esc($student->school_origin) ?></td>
                                        <td class="text-end fw-semibold">
                                            <?= number_format($student->final_score, 2) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?= esc($student->pilihan_1) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($student->accepted_major): ?>
                                                <span class="badge bg-primary">
                                                    <?= esc($student->accepted_major) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    Belum ditempatkan
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?= $statusClass ?>">
                                                <?= $statusText ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Belum ada data ranking jurusan <?= $selected_major ?>
                                    </td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>
