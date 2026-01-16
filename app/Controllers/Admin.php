<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\FailedVerificationModel;
use App\Services\SelectionEngine;
use App\Services\TelegramNotifier;
use Config\PPDB;
use Config\Services;

class Admin extends BaseController
{
    protected $studentModel;
    protected $failedVerificationModel;
    protected $selectionEngine;
    protected $telegramNotifier;
    protected $session;
    protected $ppdbConfig;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->failedVerificationModel = new FailedVerificationModel();
        $this->selectionEngine = new SelectionEngine();
        $this->telegramNotifier = new TelegramNotifier();
        $this->session = Services::session();
        $this->ppdbConfig = new PPDB();

        helper('form');
    }

    public function index()
    {
        if ($this->session->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('admin/login', ['title' => 'Login Admin']);
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $adminUser = env('ADMIN_USERNAME', 'admin');
        $adminPass = env('ADMIN_PASSWORD', 'ppdb2024');

        if ($username === $adminUser && $password === $adminPass) {
            $this->session->set([
                'admin_logged_in' => true,
                'admin_username'  => $username
            ]);

            return redirect()->to('/admin/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/admin');
    }

    public function dashboard()
    {
        $this->checkLogin();

        $data = [
            'title'           => 'Dashboard Admin',
            'total_students'  => $this->studentModel->countAll(),
            'total_failed'    => $this->failedVerificationModel->countAll(),
            'quota_filled'    => $this->getQuotaFilled(),
            'recent_students' => $this->studentModel
                ->orderBy('created_at', 'DESC')
                ->findAll(10),
            'majors'          => ['IPA', 'IPS', 'Bahasa']
        ];

        return view('admin/dashboard', $data);
    }

    public function addStudent()
    {
        $this->checkLogin();

        $data = $this->request->getPost();
        $finalScore = $this->calculateFinalScore($data);

        $studentId = $this->studentModel->insert([
            'nisn'               => $data['nisn'],
            'name'               => $data['name'],
            'birth_date'         => $data['birth_date'],
            'school_origin'      => $data['school_origin'],
            'phone'              => $data['phone'],
            'matematika'         => $data['matematika'],
            'bahasa_indonesia'   => $data['bahasa_indonesia'],
            'bahasa_inggris'     => $data['bahasa_inggris'],
            'ipa'                => $data['ipa'],
            'ips'                => $data['ips'],
            'final_score'        => $finalScore,
            'pilihan_1'          => $data['pilihan_1'],
            'pilihan_2'          => $data['pilihan_2'] ?? null,
            'pilihan_3'          => $data['pilihan_3'] ?? null,
            'status'             => 'verified',
            'verification_log'   => json_encode(['admin_override' => true])
        ]);

        $this->selectionEngine->process($studentId);

        return redirect()->to('/admin/dashboard')
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    public function resetData()
    {
        $this->checkLogin();

        if ($this->request->getMethod() === 'post') {
            $this->studentModel->truncate();
            $this->failedVerificationModel->truncate();

            $db = \Config\Database::connect();
            $db->query("ALTER TABLE students AUTO_INCREMENT = 1");
            $db->query("ALTER TABLE failed_verifications AUTO_INCREMENT = 1");

            return redirect()->to('/admin/dashboard')
                ->with('success', 'Semua data berhasil direset');
        }

        return view('admin/reset_confirm');
    }

    public function failedVerifications()
    {
        $this->checkLogin();

        return view('admin/failed_verifications', [
            'title'       => 'Data Gagal Verifikasi',
            'failed_data' => $this->failedVerificationModel->findAll()
        ]);
    }

    /**
     * 🔒 Jalankan engine seleksi untuk semua siswa
     */
    public function runSelection()
    {
        $this->checkLogin();

        // Jalankan engine seleksi tanpa siswa baru
        $result = $this->selectionEngine->process();

        // Jika dipanggil via AJAX
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => $result['status'] ?? 'success',
                'message' => $result['message'] ?? 'Seleksi berhasil dijalankan'
            ]);
        }

        // Redirect jika normal
        return redirect()->to('/admin/dashboard')
            ->with('success', $result['message'] ?? 'Seleksi berhasil dijalankan');
    }

    /**
     * 🔒 AUTH GUARD — HARUS HARD STOP
     */
    private function checkLogin(): void
    {
        if (!$this->session->get('admin_logged_in')) {
            redirect()->to('/admin')->send();
            exit;
        }
    }

    private function calculateFinalScore(array $data): float
    {
        $w = $this->ppdbConfig->weights;

        return round(
            ($data['matematika'] * $w['matematika']) +
            ($data['bahasa_indonesia'] * $w['bahasa_indonesia']) +
            ($data['bahasa_inggris'] * $w['bahasa_inggris']) +
            ($data['ipa'] * $w['ipa']) +
            ($data['ips'] * $w['ips']),
            2
        );
    }

    private function getQuotaFilled(): array
    {
        $quota = [];
        $majors = ['IPA', 'IPS', 'Bahasa'];

        foreach ($majors as $major) {
            $filled = $this->studentModel
                ->where('accepted_major', $major)
                ->countAllResults();

            $quota[$major] = [
                'filled'     => $filled,
                'total'      => 20,
                'percentage' => ($filled / 20) * 100
            ];
        }

        return $quota;
    }
}
