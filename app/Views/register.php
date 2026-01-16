<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center mt-5">
    <div class="col-xl-9 col-lg-10">

        <!-- HEADER -->
        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1">
                <i class="bi bi-pencil-square text-primary"></i>
                Formulir Pendaftaran PPDB
            </h3>
            <p class="text-muted mb-0">
                Lengkapi data dengan benar untuk mengikuti proses seleksi
            </p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">

                <?php if(session()->get('errors')): ?>
                    <div class="alert alert-danger border-0">
                        <strong><i class="bi bi-exclamation-circle"></i> Terjadi Kesalahan</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach(session()->get('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- INFO -->
                <div class="alert alert-info border-0 mb-4">
                    <h6 class="fw-semibold mb-2">
                        <i class="bi bi-lightbulb"></i> Petunjuk Pendaftaran
                    </h6>
                    <ul class="mb-0 small">
                        <li>Pilih jurusan sesuai nilai ambang batas di beranda</li>
                        <li>NISN <strong>9999</strong> akan mensimulasikan verifikasi gagal</li>
                        <li>Sistem akan memverifikasi data secara otomatis</li>
                    </ul>
                </div>

                <form id="registrationForm" action="/register/submit" method="post">
                    <?= csrf_field() ?>

                    <!-- ================= DATA PRIBADI ================= -->
                    <div class="mb-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-3">
                            <i class="bi bi-person-lines-fill text-primary"></i>
                            Data Pribadi
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NISN *</label>
                                <input type="text" class="form-control" name="nisn" id="nisn"
                                       maxlength="10" required onblur="checkNISN()">
                                <div class="form-text" id="nisnFeedback"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Lahir *</label>
                                <input type="date" class="form-control" name="birth_date" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Asal Sekolah *</label>
                                <input type="text" class="form-control" name="school_origin" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. HP *</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                        </div>
                    </div>

                    <!-- ================= NILAI ================= -->
                    <div class="mb-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-3">
                            <i class="bi bi-bar-chart-fill text-primary"></i>
                            Nilai Akademik
                        </h5>

                        <div class="alert alert-warning small border-0">
                            Bobot penilaian:
                            Matematika (30%), B. Indonesia (25%), B. Inggris (20%), IPA (15%), IPS (10%)
                        </div>

                    <div class="row">
                        <?php 
                        $subjects = [
                            'matematika' => 'Matematika',
                            'bahasa_indonesia' => 'Bahasa Indonesia', 
                            'bahasa_inggris' => 'Bahasa Inggris',
                            'ipa' => 'IPA',
                            'ips' => 'IPS'
                        ];
                        ?>
                        
                        <?php foreach($subjects as $key => $label): ?>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><?= $label ?> <span class="text-danger">*</span></label>
                            <input type="number" class="form-control subject-score" 
                                   name="<?= $key ?>" min="0" max="100" step="0.01" required
                                   oninput="calculateFinalScore()">
                        </div>
                        <?php endforeach; ?>
                    </div>

                        <!-- NILAI AKHIR -->
                        <div class="card mt-4 border-0 bg-light">
                            <div class="card-body text-center">
                                <div class="text-muted small mb-1">Nilai Akhir</div>
                                <div class="display-5 fw-bold text-primary" id="finalScoreDisplay">0.00</div>
                                <input type="hidden" name="final_score" id="finalScore">
                                <div id="scoreComparison" class="mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= JURUSAN ================= -->
                    <div class="mb-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-3">
                            <i class="bi bi-mortarboard-fill text-primary"></i>
                            Pilihan Jurusan
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Pilihan 1 *</label>
                                <select class="form-select" name="pilihan_1" id="pilihan1"
                                        onchange="updateMajorChoices()" required>
                                    <option value="">-- Pilih Jurusan --</option>
                                    <?php foreach($majors as $major): ?>
                                        <option value="<?= $major ?>"><?= $major ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Pilihan 2</label>
                                <select class="form-select" name="pilihan_2" id="pilihan2">
                                    <option value="">-- Opsional --</option>
                                    <?php foreach($majors as $major): ?>
                                        <option value="<?= $major ?>"><?= $major ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Pilihan 3</label>
                                <select class="form-select" name="pilihan_3" id="pilihan3">
                                    <option value="">-- Opsional --</option>
                                    <?php foreach($majors as $major): ?>
                                        <option value="<?= $major ?>"><?= $major ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ================= KONFIRMASI ================= -->
                    <div class="border-top pt-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" required>
                            <label class="form-check-label small">
                                Saya menyatakan seluruh data yang diisi adalah benar dan dapat dipertanggungjawabkan.
                            </label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                <i class="bi bi-send-check"></i> Kirim Pendaftaran
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Loading -->
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mb-3">Memproses Pendaftaran</h5>
                <div class="verification-log text-start mb-3">
                    <div id="logStep1">Menghubungkan ke API Server Kemendikbud...</div>
                    <div id="logStep2" class="text-muted">Validasi NISN & Sinkronisasi Data Nilai...</div>
                    <div id="logStep3" class="text-muted">Proses seleksi dan penempatan...</div>
                </div>
                <p class="text-muted">Harap tunggu, proses ini memakan waktu beberapa detik...</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const weights = {
    matematika: 0.3,
    bahasa_indonesia: 0.25,
    bahasa_inggris: 0.2,
    ipa: 0.15,
    ips: 0.1
};

const thresholds = <?= json_encode($thresholds) ?>;

function calculateFinalScore() {
    let total = 0;
    document.querySelectorAll('.subject-score').forEach(input => {
        const score = parseFloat(input.value) || 0;
        const subject = input.name;
        total += score * weights[subject];
    });
    
    const finalScore = total.toFixed(2);
    document.getElementById('finalScoreDisplay').textContent = finalScore;
    document.getElementById('finalScore').value = finalScore;
    
    // Show comparison with thresholds
    const pilihan1 = document.getElementById('pilihan1').value;
    if (pilihan1 && thresholds[pilihan1]) {
        const threshold = thresholds[pilihan1];
        const comparison = finalScore >= threshold ? 'DI ATAS' : 'DI BAWAH';
        const color = finalScore >= threshold ? 'success' : 'danger';
        const icon = finalScore >= threshold ? 'bi-arrow-up' : 'bi-arrow-down';
        
        document.getElementById('scoreComparison').innerHTML = `
            <span class="badge bg-${color}">
                <i class="bi ${icon}"></i> ${comparison} rata-rata ambang batas ${pilihan1} (${threshold})
            </span>
        `;
    }
}

function checkNISN() {
    const nisn = document.getElementById('nisn').value;
    if (nisn.length === 10) {
        fetch('/register/check-nisn', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                nisn: nisn,
                <?= csrf_token()?>: '<?= csrf_hash()?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            const feedback = document.getElementById('nisnFeedback');
            if (data.exists) {
                feedback.innerHTML = `<span class="text-danger"><i class="bi bi-x-circle"></i> ${data.message}</span>`;
            } else {
                feedback.innerHTML = `<span class="text-success"><i class="bi bi-check-circle"></i> ${data.message}</span>`;
            }
        });
    }
}

function updateMajorChoices() {
    const pilihan1 = document.getElementById('pilihan1').value;
    const pilihan2 = document.getElementById('pilihan2');
    const pilihan3 = document.getElementById('pilihan3');
    
    // Reset
    pilihan2.value = '';
    pilihan3.value = '';
    
    // Auto-fill based on selection rules
    if (pilihan1 === 'IPA') {
        pilihan2.innerHTML = '<option value="IPS">IPS</option><option value="Bahasa">Bahasa</option>';
        pilihan3.innerHTML = '<option value="Bahasa">Bahasa</option><option value="IPS">IPS</option>';
    } else if (pilihan1 === 'IPS') {
        pilihan2.innerHTML = '<option value="Bahasa">Bahasa</option>';
        pilihan3.innerHTML = '<option value="">-- Opsional --</option>';
        pilihan2.value = 'Bahasa';
    } else if (pilihan1 === 'Bahasa') {
        pilihan2.innerHTML = '<option value="">-- Opsional --</option>';
        pilihan3.innerHTML = '<option value="">-- Opsional --</option>';
    }
}

function resetForm() {
    if (confirm('Apakah Anda yakin ingin mengosongkan semua form?')) {
        document.getElementById('registrationForm').reset();
        document.getElementById('finalScoreDisplay').textContent = '0.00';
        document.getElementById('scoreComparison').innerHTML = '';
    }
}

// Auto-fill based on URL parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const major = urlParams.get('major');
    if (major) {
        document.getElementById('pilihan1').value = major;
        updateMajorChoices();
    }
    
    // Form submission with loading animation
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
        modal.show();
        
        // Simulate API verification steps
        setTimeout(() => {
            document.getElementById('logStep1').classList.add('text-success');
            document.getElementById('logStep2').classList.remove('text-muted');
        }, 1500);
        
        setTimeout(() => {
            document.getElementById('logStep2').classList.add('text-success');
            document.getElementById('logStep3').classList.remove('text-muted');
        }, 3000);
        
        setTimeout(() => {
            document.getElementById('logStep3').classList.add('text-success');
            // Submit the form
            e.target.submit();
        }, 4500);
    });
});
</script>
<?= $this->endSection() ?>