<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <!-- Header Dashboard -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="bg-primary rounded p-2">
                    <i class="fas fa-tachometer-alt fa-lg text-white"></i>
                </div>
                <div>
                    <h1 class="h3 mb-0 text-gray-800 fw-bold">Dashboard Admin</h1>
                    <p class="text-muted mb-0">Sistem Seleksi PPDB - Admin Panel</p>
                </div>
            </div>
        </div>
        
        <div class="mt-3 mt-md-0">
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <p class="mb-0 text-muted small">Login sebagai</p>
                    <p class="mb-0 fw-bold"><?= session()->get('admin_username') ?></p>
                </div>
                <div class="vr d-none d-md-block"></div>
                <a href="/admin/logout" class="btn btn-danger btn-sm px-3">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Statistik Cards Grid -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small fw-medium">TOTAL SISWA</p>
                            <h2 class="fw-bold mb-0 text-primary"><?= $total_students ?></h2>
                            <div class="mt-3">
                                <span class="badge bg-primary-subtle text-primary fw-normal">
                                    <i class="fas fa-users me-1"></i>Terdaftar
                                </span>
                            </div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-user-graduate fa-lg text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small fw-medium">GAGAL VERIFIKASI</p>
                            <h2 class="fw-bold mb-0 text-danger"><?= $total_failed ?></h2>
                            <div class="mt-3">
                                <a href="/admin/failed-verifications" class="text-decoration-none small">
                                    <i class="fas fa-exclamation-circle me-1"></i>Lihat detail
                                </a>
                            </div>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="fas fa-exclamation-triangle fa-lg text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small fw-medium">KUOTA TERISI</p>
                            <?php 
                            $total_filled = array_sum(array_column($quota_filled, 'filled'));
                            $percentage = ($total_filled / 60) * 100;
                            ?>
                            <h2 class="fw-bold mb-0 text-success"><?= $total_filled ?>/60</h2>
                            <div class="mt-3">
                                <span class="badge bg-success-subtle text-success fw-normal">
                                    <?= number_format($percentage, 1) ?>% Terisi
                                </span>
                            </div>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-chart-pie fa-lg text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-2 small fw-medium">PROGRESS SELEKSI</p>
                            <?php 
                            $accepted_count = count(array_filter($recent_students, function($s) { 
                                return $s->status == 'accepted'; 
                            }));
                            $progress_percentage = count($recent_students) > 0 ? ($accepted_count / count($recent_students)) * 100 : 0;
                            ?>
                            <h2 class="fw-bold mb-0 text-warning"><?= $accepted_count ?>/<?= count($recent_students) ?></h2>
                            <div class="mt-3">
                                <span class="badge bg-warning-subtle text-warning fw-normal">
                                    <i class="fas fa-check-circle me-1"></i><?= number_format($progress_percentage, 1) ?>%
                                </span>
                            </div>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-cogs fa-lg text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column - 8 Grid -->
        <div class="col-xl-8">
            <!-- Form Tambah Siswa -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-user-plus text-primary me-2"></i>
                                Tambah Siswa Manual
                            </h5>
                            <p class="text-muted small mb-0">Admin Override - Tambah data siswa secara manual</p>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generateDummyData()">
                            <i class="fas fa-magic me-1"></i>Generate Dummy
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <form id="addStudentForm" action="/admin/add-student" method="post" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        
                        <!-- Data Pribadi -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 text-primary">
                                <i class="fas fa-id-card me-2"></i>Data Pribadi
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium mb-1">
                                        NISN <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           name="nisn" 
                                           required
                                           placeholder="Masukkan NISN siswa">
                                    <div class="invalid-feedback small">
                                        Harap isi NISN dengan benar
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium mb-1">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           name="name" 
                                           required
                                           placeholder="Nama lengkap siswa">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium mb-1">
                                        Asal Sekolah <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           name="school_origin" 
                                           required
                                           placeholder="Nama sekolah asal">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label small fw-medium mb-1">
                                        No. Telepon <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           name="phone" 
                                           required
                                           placeholder="08xxxxxxxxxx">
                                    <input type="hidden" name="birth_date">
                                </div>
                            </div>
                        </div>

                        <!-- Nilai Akademik -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0 text-primary">
                                    <i class="fas fa-chart-line me-2"></i>Nilai Akademik
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="autoGenerateScores()">
                                    <i class="fas fa-random me-1"></i>Auto Generate
                                </button>
                            </div>
                            
                            <div class="row g-2">
                                <?php 
                                $subjects = [
                                    'matematika' => ['Matematika', '30%'],
                                    'bahasa_indonesia' => ['B. Indonesia', '25%'],
                                    'bahasa_inggris' => ['B. Inggris', '20%'],
                                    'ipa' => ['IPA', '15%'],
                                    'ips' => ['IPS', '10%']
                                ];
                                ?>
                                
                                <?php foreach($subjects as $key => [$label, $weight]): ?>
                                <div class="col-md-2 col-6">
                                    <div class="border rounded p-2 h-100">
                                        <label class="form-label small fw-medium mb-1"><?= $label ?></label>
                                        <input type="number" 
                                               class="form-control form-control-sm border-0 score-input" 
                                               name="<?= $key ?>" 
                                               min="0" 
                                               max="100" 
                                               step="0.01" 
                                               required
                                               placeholder="0-100"
                                               oninput="calculateAdminScore()">
                                        <div class="text-muted xsmall mt-1">Bobot: <?= $weight ?></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <div class="col-md-2 col-6">
                                    <div class="border rounded p-2 h-100 bg-light">
                                        <label class="form-label small fw-medium mb-1">Nilai Akhir</label>
                                        <div class="d-flex align-items-center">
                                            <input type="text" 
                                                   class="form-control form-control-sm border-0 bg-transparent fs-6 fw-bold text-primary" 
                                                   id="adminFinalScore" 
                                                   readonly>
                                            <span class="ms-1 small">%</span>
                                        </div>
                                        <div class="text-muted xsmall mt-1">Terhitung otomatis</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pilihan Jurusan -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 text-primary">
                                <i class="fas fa-graduation-cap me-2"></i>Pilihan Jurusan
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="border rounded p-3">
                                        <label class="form-label small fw-medium mb-1">
                                            Pilihan 1 <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-sm border-0" name="pilihan_1" required>
                                            <option value="" disabled selected>-- Pilih Jurusan --</option>
                                            <?php foreach($majors as $major): ?>
                                            <option value="<?= $major ?>"><?= $major ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="border rounded p-3">
                                        <label class="form-label small fw-medium mb-1">
                                            Pilihan 2 <span class="text-muted">(Opsional)</span>
                                        </label>
                                        <select class="form-select form-select-sm border-0" name="pilihan_2">
                                            <option value="" selected>-- Opsional --</option>
                                            <?php foreach($majors as $major): ?>
                                            <option value="<?= $major ?>"><?= $major ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="border rounded p-3">
                                        <label class="form-label small fw-medium mb-1">
                                            Pilihan 3 <span class="text-muted">(Opsional)</span>
                                        </label>
                                        <select class="form-select form-select-sm border-0" name="pilihan_3">
                                            <option value="" selected>-- Opsional --</option>
                                            <?php foreach($majors as $major): ?>
                                            <option value="<?= $major ?>"><?= $major ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="border-top pt-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-outline-secondary btn-sm px-4">
                                    <i class="fas fa-redo me-1"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm px-4">
                                    <i class="fas fa-save me-1"></i>Simpan Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Pendaftar Terbaru -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-history text-primary me-2"></i>
                                Pendaftar Terbaru
                            </h5>
                            <p class="text-muted small mb-0">10 data pendaftar terakhir</p>
                        </div>
                        <a href="/ranking" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list me-1"></i>Lihat Semua
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr class="small">
                                    <th class="ps-4 fw-medium">NISN</th>
                                    <th class="fw-medium">Nama</th>
                                    <th class="text-center fw-medium">Nilai</th>
                                    <th class="fw-medium">Pilihan 1</th>
                                    <th class="text-center fw-medium">Status</th>
                                    <th class="pe-4 text-center fw-medium">Diterima</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_students as $student): ?>
                                <tr class="align-middle small">
                                    <td class="ps-4 fw-medium"><?= $student->nisn ?></td>
                                    <td>
                                        <div class="fw-medium"><?= $student->name ?></div>
                                        <div class="text-muted xsmall"><?= $student->school_origin ?></div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill px-3 py-1">
                                            <?= number_format($student->final_score, 2) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                            <?= $student->pilihan_1 ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-<?= $student->status == 'accepted' ? 'success' : 'warning' ?> bg-opacity-10 text-<?= $student->status == 'accepted' ? 'success' : 'warning' ?> px-3 py-1">
                                            <i class="fas fa-<?= $student->status == 'accepted' ? 'check-circle' : 'clock' ?> me-1"></i>
                                            <?= ucfirst($student->status) ?>
                                        </span>
                                    </td>
                                    <td class="pe-4 text-center">
                                        <?php if($student->accepted_major): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-1">
                                            <?= $student->accepted_major ?>
                                        </span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1">
                                            Menunggu
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - 4 Grid -->
        <div class="col-xl-4">
            <!-- Admin Tools (Pindah ke atas karena lebih penting) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded">
                            <i class="fas fa-tools text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Admin Tools</h5>
                            <p class="text-muted small mb-0">Fitur administrasi sistem</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/admin/failed-verifications" class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-between">
                            <span>
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Data Gagal Verifikasi
                            </span>
                            <span class="badge bg-danger"><?= $total_failed ?></span>
                        </a>
                        
                        <button onclick="runSelectionEngine()" class="btn btn-outline-warning btn-sm d-flex align-items-center justify-content-between">
                            <span>
                                <i class="fas fa-play-circle me-2"></i>
                                Jalankan Engine Seleksi
                            </span>
                            <i class="fas fa-chevron-right small"></i>
                        </button>
                        
                        <a href="/ranking" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-between">
                            <span>
                                <i class="fas fa-trophy me-2"></i>
                                Lihat Ranking Lengkap
                            </span>
                            <i class="fas fa-chevron-right small"></i>
                        </a>
                        
                        <a href="/admin/reset-data" 
                           class="btn btn-outline-dark btn-sm d-flex align-items-center justify-content-between"
                           onclick="return confirmResetData()">
                            <span>
                                <i class="fas fa-trash-alt me-2"></i>
                                Reset Semua Data
                            </span>
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                        </a>
                    </div>
                    
                    <!-- Demo Skenario Quick Tips -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold small mb-3">
                            <i class="fas fa-lightbulb text-warning me-1"></i>
                            Demo Skenario
                        </h6>
                        
                        <div class="list-group list-group-flush small">
                            <div class="list-group-item border-0 px-0 py-1 d-flex align-items-start">
                                <div class="bg-success bg-opacity-10 rounded-circle p-1 me-2 mt-1">
                                    <i class="fas fa-check text-success fa-xs"></i>
                                </div>
                                <div>
                                    <span class="fw-medium">NISN 9999</span> untuk simulasi gagal verifikasi
                                </div>
                            </div>
                            
                            <div class="list-group-item border-0 px-0 py-1 d-flex align-items-start">
                                <div class="bg-info bg-opacity-10 rounded-circle p-1 me-2 mt-1">
                                    <i class="fas fa-bolt text-info fa-xs"></i>
                                </div>
                                <div>
                                    Nilai tinggi untuk trigger domino effect
                                </div>
                            </div>
                            
                            <div class="list-group-item border-0 px-0 py-1 d-flex align-items-start">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-1 me-2 mt-1">
                                    <i class="fas fa-paper-plane text-primary fa-xs"></i>
                                </div>
                                <div>
                                    Cek notifikasi real-time Telegram
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kuota Jurusan -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-info bg-opacity-10 p-2 rounded">
                            <i class="fas fa-chart-pie text-info"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Kuota Jurusan</h5>
                            <p class="text-muted small mb-0">Kapasitas terisi per jurusan</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-muted small">Total Terisi</span>
                            <h4 class="mb-0 fw-bold"><?= $total_filled ?>/60</h4>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary px-3 py-2">
                                <?= number_format(($total_filled/60)*100, 1) ?>%
                            </span>
                        </div>
                    </div>
                    
                    <?php foreach($quota_filled as $major => $data): 
                        $percentage = min(100, $data['percentage']);
                        $color = $percentage >= 100 ? 'danger' : ($percentage >= 80 ? 'warning' : 'success');
                        $icon = $percentage >= 100 ? 'fa-times-circle' : ($percentage >= 80 ? 'fa-exclamation-circle' : 'fa-check-circle');
                    ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-medium small"><?= $major ?></span>
                            <span class="small fw-bold"><?= $data['filled'] ?>/<?= $data['total'] ?></span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2">
                            <div class="flex-grow-1">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-<?= $color ?>" 
                                         style="width: <?= $percentage ?>%"
                                         role="progressbar">
                                    </div>
                                </div>
                            </div>
                            <div class="text-<?= $color ?>">
                                <i class="fas <?= $icon ?> fa-xs"></i>
                                <span class="small fw-bold ms-1"><?= number_format($percentage, 1) ?>%</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <!-- Legend -->
                    <div class="border-top pt-3 mt-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <div class="bg-success rounded" style="width: 10px; height: 10px;"></div>
                                    <span class="xsmall text-muted">Tersedia</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <div class="bg-warning rounded" style="width: 10px; height: 10px;"></div>
                                    <span class="xsmall text-muted">Hampir Penuh</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <div class="bg-danger rounded" style="width: 10px; height: 10px;"></div>
                                    <span class="xsmall text-muted">Penuh</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <div class="bg-primary rounded" style="width: 10px; height: 10px;"></div>
                                    <span class="xsmall text-muted">Total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Calculate final score
function calculateAdminScore() {
    const weights = {
        matematika: 0.3,
        bahasa_indonesia: 0.25,
        bahasa_inggris: 0.2,
        ipa: 0.15,
        ips: 0.1
    };
    
    let total = 0;
    let allFilled = true;
    
    Object.keys(weights).forEach(subject => {
        const input = document.querySelector(`input[name="${subject}"]`);
        const score = parseFloat(input.value);
        
        if (!isNaN(score)) {
            total += score * weights[subject];
        } else {
            allFilled = false;
        }
    });
    
    const finalScoreInput = document.getElementById('adminFinalScore');
    if (allFilled) {
        finalScoreInput.value = total.toFixed(2);
        finalScoreInput.classList.remove('text-secondary');
        finalScoreInput.classList.add('text-primary');
    } else {
        finalScoreInput.value = '-';
        finalScoreInput.classList.remove('text-primary');
        finalScoreInput.classList.add('text-secondary');
    }
}

// Auto generate random scores
function autoGenerateScores() {
    const subjects = ['matematika', 'bahasa_indonesia', 'bahasa_inggris', 'ipa', 'ips'];
    
    subjects.forEach(subject => {
        const input = document.querySelector(`input[name="${subject}"]`);
        // Generate random score between 70-95
        const randomScore = (70 + Math.random() * 25).toFixed(2);
        input.value = randomScore;
    });
    
    calculateAdminScore();
    showToast('Nilai berhasil digenerate secara acak!', 'success');
}

// Generate complete dummy data
function generateDummyData() {
    // Clear form first
    document.getElementById('addStudentForm').reset();
    
    // Generate NISN
    const nisn = Math.floor(Math.random() * 2) === 0 ? '9999' : '9' + Math.floor(Math.random() * 1000000000).toString().padStart(9, '0');
    document.querySelector('input[name="nisn"]').value = nisn;
    
    // Generate name
    const firstNames = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hadi', 'Indra', 'Joko'];
    const lastNames = ['Santoso', 'Wijaya', 'Kusuma', 'Pratiwi', 'Nugroho', 'Putra', 'Sari', 'Rahman', 'Siregar'];
    const randomName = `${firstNames[Math.floor(Math.random() * firstNames.length)]} ${lastNames[Math.floor(Math.random() * lastNames.length)]}`;
    document.querySelector('input[name="name"]').value = randomName;
    
    // Generate school
    const schools = ['SMP Negeri 1 Jakarta', 'SMP Negeri 2 Bandung', 'SMP Negeri 3 Surabaya', 'SMP Muhammadiyah', 'SMP Islam Terpadu'];
    document.querySelector('input[name="school_origin"]').value = schools[Math.floor(Math.random() * schools.length)];
    
    // Generate phone
    const phone = '08' + Math.floor(100000000 + Math.random() * 900000000);
    document.querySelector('input[name="phone"]').value = phone;
    
    // Generate birth date
    const year = 2005 + Math.floor(Math.random() * 3);
    const month = String(Math.floor(Math.random() * 12) + 1).padStart(2, '0');
    const day = String(Math.floor(Math.random() * 28) + 1).padStart(2, '0');
    document.querySelector('input[name="birth_date"]').value = `${year}-${month}-${day}`;
    
    // Auto generate scores
    autoGenerateScores();
    
    // Select random major
    const majorOptions = document.querySelectorAll('select[name="pilihan_1"] option');
    const randomMajorIndex = Math.floor(Math.random() * (majorOptions.length - 1)) + 1;
    document.querySelector('select[name="pilihan_1"]').value = majorOptions[randomMajorIndex].value;
    
    // Show notification
    if (nisn === '9999') {
        showToast('⚠️ Data dummy dengan NISN 9999 digenerate (simulasi gagal verifikasi)', 'warning');
    } else {
        showToast('Data dummy berhasil digenerate!', 'success');
    }
}

// Run selection engine
async function runSelectionEngine() {
    if (!confirm('Jalankan engine seleksi?\n\nSistem akan menghitung ulang semua ranking dan penempatan jurusan.')) {
        return;
    }
    
    try {
        const response = await fetch('/admin/run-selection', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="<?= csrf_token() ?>"]')?.value || ''
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('✅ Engine seleksi berhasil dijalankan!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || '❌ Terjadi kesalahan', 'error');
        }
    } catch (error) {
        showToast('❌ Koneksi terputus. Silakan coba lagi.', 'error');
    }
}

// Fungsi confirm untuk reset data
function confirmResetData() {
    return confirm('⚠️ PERINGATAN: Reset Semua Data\n\n' +
                   'Tindakan ini akan menghapus:\n' +
                   '• Semua data siswa\n' +
                   '• Semua ranking\n' +
                   '• Semua penempatan jurusan\n\n' +
                   'Tindakan ini TIDAK DAPAT DIBATALKAN!\n\n' +
                   'Yakin ingin melanjutkan?');
}

// Toast notification
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || (() => {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1060';
        document.body.appendChild(container);
        return container;
    })();
    
    const toastId = 'toast-' + Date.now();
    const typeConfig = {
        success: { icon: 'check-circle', bg: 'success', title: 'Sukses' },
        error: { icon: 'exclamation-circle', bg: 'danger', title: 'Error' },
        warning: { icon: 'exclamation-triangle', bg: 'warning', title: 'Peringatan' },
        info: { icon: 'info-circle', bg: 'info', title: 'Info' }
    };
    
    const config = typeConfig[type] || typeConfig.info;
    
    const toastHTML = `
    <div id="${toastId}" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-${config.bg} text-white">
            <i class="fas fa-${config.icon} me-2"></i>
            <strong class="me-auto">${config.title}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.remove();
        }
    }, 5000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
    
    // Initialize score calculation
    calculateAdminScore();
});
</script>

<!-- Add Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --bs-primary: #4361ee;
    --bs-success: #06d6a0;
    --bs-danger: #ef476f;
    --bs-warning: #ffd166;
    --bs-info: #118ab2;
}

body {
    background-color: #f8f9fa;
}

.card {
    border-radius: 0.75rem;
    border: 1px solid rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,0.08);
    background-color: rgba(255,255,255,0.9);
}

.table th {
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    background-color: #f8f9fa !important;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    padding-top: 1rem;
    padding-bottom: 1rem;
    vertical-align: middle;
    border-color: #f1f3f4;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

.btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.form-control, .form-select {
    border-radius: 0.5rem;
    border: 1px solid #e0e0e0;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
}

.form-control-sm {
    padding: 0.375rem 0.75rem;
}

.progress {
    border-radius: 0.5rem;
    overflow: hidden;
}

.progress-bar {
    border-radius: 0.5rem;
}

.bg-primary-subtle {
    background-color: rgba(67, 97, 238, 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(6, 214, 160, 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(239, 71, 111, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 209, 102, 0.1) !important;
}

.xsmall {
    font-size: 0.7rem;
}

.vr {
    width: 1px;
    background-color: #dee2e6;
    opacity: 1;
    height: 2rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.03);
}

.toast {
    border-radius: 0.5rem;
    border: 1px solid rgba(0,0,0,0.1);
}

#toast-container .toast {
    min-width: 300px;
    max-width: 350px;
}

/* Button hover effects */
.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white !important;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    color: #212529 !important;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    color: white !important;
}

.btn-outline-dark:hover {
    background-color: #212529;
    color: white !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .col-xl-4 {
        margin-top: 1rem;
    }
    
    .card {
        margin-bottom: 1rem !important;
    }
}
</style>
<?= $this->endSection() ?>