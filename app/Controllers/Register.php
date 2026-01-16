<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\FailedVerificationModel;
use App\Services\SelectionEngine;
use App\Services\TelegramNotifier;
use Config\PPDB;

class Register extends BaseController
{
    protected $studentModel;
    protected $failedVerificationModel;
    protected $selectionEngine;
    protected $telegramNotifier;
    protected $ppdbConfig;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->failedVerificationModel = new FailedVerificationModel();
        $this->selectionEngine = new SelectionEngine();
        $this->telegramNotifier = new TelegramNotifier();
        $this->ppdbConfig = new PPDB();
    }

    public function index()
    {
        $data = [
            'title' => 'Pendaftaran Siswa Baru',
            'majors' => ['IPA', 'IPS', 'Bahasa'],
            'thresholds' => $this->getThresholdValues()
        ];
        
        return view('register', $data);
    }
    
    public function submit()
    {
        if (!$this->validate($this->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = $this->request->getPost();
        
        // Simulasi verifikasi API Kemendikbud
        if ($data['nisn'] === $this->ppdbConfig->thresholdNISN) {
            $this->failedVerificationModel->save([
                'nisn' => $data['nisn'],
                'name' => $data['name'],
                'reason' => 'NISN tidak valid di sistem Kemendikbud'
            ]);
            
            return view('verification_failed', [
                'title' => 'Verifikasi Gagal',
                'nisn' => $data['nisn']
            ]);
        }
        
        // Hitung nilai akhir
        $finalScore = $this->calculateFinalScore($data);
        
        // Simpan data siswa
        $studentData = [
            'nisn' => $data['nisn'],
            'name' => $data['name'],
            'birth_date' => $data['birth_date'],
            'school_origin' => $data['school_origin'],
            'phone' => $data['phone'],
            'matematika' => $data['matematika'],
            'bahasa_indonesia' => $data['bahasa_indonesia'],
            'bahasa_inggris' => $data['bahasa_inggris'],
            'ipa' => $data['ipa'],
            'ips' => $data['ips'],
            'final_score' => $finalScore,
            'pilihan_1' => $data['pilihan_1'],
            'pilihan_2' => $data['pilihan_2'] ?? null,
            'pilihan_3' => $data['pilihan_3'] ?? null,
            'status' => 'pending',
            'verification_log' => json_encode([
                'step1' => 'Menghubungkan ke API Server Kemendikbud... OK',
                'step2' => 'Validasi NISN & Sinkronisasi Data Nilai... OK',
                'step3' => 'Verifikasi data lengkap... OK'
            ])
        ];
        
        $studentId = $this->studentModel->insert($studentData);
        
        // Jalankan engine seleksi
        $selectionResult = $this->selectionEngine->process($studentId);
        
        // --- LOGIKA NOTIFIKASI TELEGRAM ---
        if ($this->ppdbConfig->telegramConfig['enabled']) {
            // Ambil data siswa terbaru setelah selection engine
            $latestStudentData = $this->studentModel->find($studentId);

            // Jika ingin mengirim notifikasi gabungan, buat array siswa baru, tergeser, dan keluar
            $newStudents = [
                (array)$latestStudentData
            ];

            // Engine bisa memberikan data siswa tergeser & keluar, contoh:
            $shiftedStudents = $selectionResult['shifted'] ?? [];
            $rejectedStudents = $selectionResult['rejected'] ?? [];

            $this->telegramNotifier->sendCombinedNotification($newStudents, $shiftedStudents, $rejectedStudents);
        }
        
        return view('registration_success', [
            'title' => 'Pendaftaran Berhasil',
            'student' => $studentData,
            'result' => $selectionResult,
            'registration_id' => $studentId
        ]);
    }

    public function checkNISN()
    {
        $nisn = $this->request->getPost('nisn');
        $exists = $this->studentModel->where('nisn', $nisn)->first();
        
        return $this->response->setJSON([
            'exists' => !empty($exists),
            'message' => $exists ? 'NISN sudah terdaftar' : 'NISN tersedia'
        ]);
    }
    
    private function getValidationRules()
    {
        return [
            'nisn' => 'required|numeric|min_length[10]|max_length[10]',
            'name' => 'required|min_length[3]',
            'birth_date' => 'required|valid_date',
            'school_origin' => 'required',
            'phone' => 'required',
            'matematika' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
            'bahasa_indonesia' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
            'bahasa_inggris' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
            'ipa' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
            'ips' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pilihan_1' => 'required|in_list[IPA,IPS,Bahasa]'
        ];
    }
    
    private function calculateFinalScore($data)
    {
        $weights = $this->ppdbConfig->weights;
        
        $score = (
            $data['matematika'] * $weights['matematika'] +
            $data['bahasa_indonesia'] * $weights['bahasa_indonesia'] +
            $data['bahasa_inggris'] * $weights['bahasa_inggris'] +
            $data['ipa'] * $weights['ipa'] +
            $data['ips'] * $weights['ips']
        );
        
        return round($score, 2);
    }
    
    private function getThresholdValues()
    {
        $thresholds = [];
        $majors = ['IPA', 'IPS', 'Bahasa'];
        
        foreach ($majors as $major) {
            $students = $this->studentModel
                ->where('accepted_major', $major)
                ->orderBy('final_score', 'DESC')
                ->findAll(10);
            
            $thresholds[$major] = count($students) >= 10 
                ? ($students[9]->final_score ?? 0) 
                : 0;
        }
        
        return $thresholds;
    }
}
