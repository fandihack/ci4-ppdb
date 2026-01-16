<?php

namespace App\Services;

use App\Models\StudentModel;
use Config\PPDB;

class SelectionEngine
{
    protected $studentModel;
    protected $ppdbConfig;
    protected $telegramNotifier;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->ppdbConfig = new PPDB();
        $this->telegramNotifier = new TelegramNotifier();
    }

    /**
     * Jalankan proses seleksi
     * @param int|null $newStudentId ID siswa baru (opsional untuk notifikasi)
     */
    public function process($newStudentId = null)
    {
        // 1. Simpan kondisi ranking SEBELUM seleksi dijalankan
        $beforeSelection = $this->getCurrentStatus();

        // 2. Reset status semua siswa
        $this->resetAllStudents();

        // 3. Jalankan seleksi utama
        $this->runSelection();

        // 4. Jika ada siswa baru, bandingkan kondisi dan kirim notifikasi gabungan
        if ($newStudentId) {
            $this->detectAndNotifyChanges($newStudentId, $beforeSelection);
        }

        // 5. Hitung ulang ranking (opsional)
        $this->recalculateAllRankings();

        return [
            'status' => 'success',
            'message' => 'Seleksi berhasil dijalankan',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Ambil status semua siswa sebelum seleksi
     */
    private function getCurrentStatus()
    {
        $students = $this->studentModel->findAll();
        $statusMap = [];
        foreach ($students as $s) {
            $statusMap[$s->id] = [
                'major'  => $s->accepted_major,
                'status' => $s->status
            ];
        }
        return $statusMap;
    }

    /**
     * Reset semua siswa menjadi pending tanpa jurusan
     */
    private function resetAllStudents()
    {
        $db = db_connect();
        $db->table('students')->update([
            'accepted_major' => null,
            'status' => 'pending'
        ]);
    }

    /**
     * Jalankan proses seleksi
     */
    private function runSelection()
    {
        // Ambil semua siswa, urutkan berdasarkan nilai tertinggi
        $allStudents = $this->studentModel
            ->orderBy('final_score', 'DESC')
            ->orderBy('created_at', 'ASC')
            ->findAll();

        // Inisialisasi kuota per jurusan
        $remainingQuota = $this->ppdbConfig->quota;

        foreach ($allStudents as $student) {
            $placed = false;

            // Cek pilihan 1 → 2 → 3
            for ($p = 1; $p <= 3; $p++) {
                $choice = $student->{"pilihan_$p"};

                if ($choice && isset($remainingQuota[$choice]) && $remainingQuota[$choice] > 0) {
                    // Tempatkan siswa
                    $this->studentModel->update($student->id, [
                        'accepted_major' => $choice,
                        'status' => 'accepted'
                    ]);

                    // Kurangi kuota
                    $remainingQuota[$choice]--;
                    $placed = true;
                    break; // siswa sudah ditempatkan, lanjut ke siswa berikutnya
                }
            }

            // Jika tidak dapat jurusan apapun
            if (!$placed) {
                $this->studentModel->update($student->id, [
                    'status' => 'waitlisted'
                ]);
            }
        }
    }

    /**
     * Bandingkan status lama dan baru untuk notifikasi
     */
    private function detectAndNotifyChanges($newStudentId, $oldStatusMap)
    {
        $newStudent = $this->studentModel->find($newStudentId);
        $allStudents = $this->studentModel->findAll();

        $shiftedStudents = [];
        $rejectedStudents = [];

        foreach ($allStudents as $current) {
            // Abaikan siswa baru
            if ($current->id == $newStudentId) continue;

            $old = $oldStatusMap[$current->id] ?? null;
            if (!$old) continue;

            // Siswa lama tergeser keluar (Waiting List)
            if ($old['status'] === 'accepted' && $current->status === 'waitlisted') {
                $rejectedStudents[] = $current;
            }
            // Siswa lama pindah jurusan (tetap diterima tapi jurusan berubah)
            elseif ($old['status'] === 'accepted' && $current->status === 'accepted' && $old['major'] !== $current->accepted_major) {
                $shiftedStudents[] = $current;
            }
        }

        // Kirim notifikasi gabungan ke Telegram jika aktif
        if ($this->ppdbConfig->telegramConfig['enabled']) {
            $this->telegramNotifier->sendCombinedNotification(
                [$newStudent],
                $shiftedStudents,
                $rejectedStudents
            );
        }
    }

    /**
     * Hitung ulang ranking (opsional)
     */
    private function recalculateAllRankings()
    {
        // Misal ingin simpan ranking global atau ranking per jurusan
        // Kosongkan dulu, atau bisa implementasikan sesuai kebutuhan
    }
}
